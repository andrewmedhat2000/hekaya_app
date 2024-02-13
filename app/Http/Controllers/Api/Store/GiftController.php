<?php

namespace App\Http\Controllers\Api\Store;

use Carbon\Carbon;
use App\Models\Gift;
use App\Models\Room;
use App\Models\User;
use App\Models\Badge;
use App\Models\Emoji;
use App\Models\Frame;
use App\Events\GiftSent;
use App\Models\Settings;
use App\Models\FrameUser;
use App\Models\GiftSends;
use App\Models\LoginSign;
use App\Models\EmojiSends;
use App\Models\RoomsDrivers;

use Illuminate\Http\Request;
use App\Events\ExpensiveGift;
use App\Jobs\FCMNotification;
use App\Models\CurrencyWorth;
use App\Events\CharismaCounter;
use App\Models\GiftUserHistory;
use App\Models\UserStreamerInfo;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use App\Jobs\ExpensiveGift as JobsExpensiveGift;

class GiftController extends BaseController
{
    //
    public function getGifts(Request $request)
    {
        try {

            $user = Auth::user();


            if (!$user) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'Wrong credentials',
                ]);
            }

            $gifts = Gift::all();

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                'gifts' => $gifts,
            ]);
        } catch (\Exception $ex) {


            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 407,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 407);
        }
    }

    public function getMyGifts(Request $request)
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'Wrong credentials',
                ], 422);
            }

            $gifts = $user->giftsHistory()->wherePivot('status', 0)->get();


            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                'gifts' => $gifts,
            ]);
        } catch (\Exception $ex) {


            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 407,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 407);
        }
    }

    public function sendGift(Request $request) //deprecated
    {
        try {
            $validator = Validator::make($request->all(), [
                'room_id'         => 'required|exists:rooms,id',
                'gift_id'         => 'required|exists:gifts,id',
                'to_user_id'         => 'required|exists:users,id',
            ]);


            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }


            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'Wrong credentials',
                ], 422);
            }

            $gift = Gift::where('id', $request->get('gift_id'))->first();

            if (!$gift) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Gift Not Found',
                ], 404);
            }

            $gift = $user->giftsHistory()->wherePivot('gift_id', '=', $request->get('gift_id'))->wherePivot('status', '=', 0)->first();

            if (!$gift) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'User hasnot buy this gift, buy it first',
                ], 409);
            }

            DB::beginTransaction();

            //attach sender and reveiver for the gift
            $gift->getUsersSend()->attach([
                $user->id => ['to_user_id' => $request->get('to_user_id'), 'room_id' => $request->input('room_id')],
            ]);

            //delete the gift from the history of the user
            $gift_user = GiftUserHistory::where('user_id', $user->id)
                ->where('gift_id', $request->get('gift_id'))->where('status', '=', 0)->first()->update(['status' => 1]);


            $ratio = CurrencyWorth::first();
            if(!$ratio) $ratio = CurrencyWorth::create(['diamond' => 1, 'gem' => 1, 'charisma' => 1,  'contribution' => 1]);

            $gem_factor = $ratio->gem / $ratio->diamond;
            $to_user = User::where('id', $request->get('to_user_id'))->first();
            $to_user->gems += $gift->price * $gem_factor;
            $to_user->save();

            if ($to_user->hasRole('streamer')) {
                $user_streamer = $to_user->currentStreamerAdminsPivot;

                if($user_streamer)
                    $user_streamer->streamerInfo()->where('mins', 0)->increment('gold', $gift->price);
            }


            $contribution_factor = $ratio->contribution / $ratio->diamond;
            $user->contribution += $gift->price * $contribution_factor;
            $user->save();

            broadcast(new GiftSent(Room::find($request->room_id), $gift, $user, $to_user));

            DB::commit();

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                // 'notification' => $notification ?? ''
            ]);
        } catch (\Exception $ex) {

            DB::rollBack();

            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 407,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 407);
        }
    }

    public function BuyAndSendGift(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'room_id'         => 'required|exists:rooms,id',
                'gift_id'         => 'required|exists:gifts,id',
                'to_user_id'         => 'required|exists:users,id',
            ]);

            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }

            $gift = Gift::where('id', $request->get('gift_id'))->first();
            $user = Auth::user();
            $to_user = User::where('id', $request->get('to_user_id'))->first();

            if($user->diamonds < $gift->price){
                return response()->json([
                    'status_code' => 409,
                    'message' => 'Insufficient Diamonds Creditt',
                ]);
            }

            DB::transaction(function() use($request, $user, $gift, $to_user){

                $ratio = CurrencyWorth::firstOrCreate(['diamond' => 1, 'gem' => 1, 'charisma' => 1,  'contribution' => 1]);

                $contribution_factor = $ratio->contribution / $ratio->diamond;
                $gem_factor = $ratio->gem / $ratio->diamond;
                $charisma_factor = $ratio->charisma / $ratio->diamond;

                $user->decrement('diamonds', $gift->price);
                $user->increment('contribution', $gift->price * $contribution_factor);

                $gift->getUsersSend()->attach([
                    $user->id => [
                        'to_user_id' => $request->get('to_user_id'),
                        'room_id' => $request->input('room_id'),
                        'price' => $gift->price,
                    ],
                ]);

                $charisma = $gift->price * $charisma_factor;
                $to_user->increment('gems', $gift->price);
                $to_user->increment('charisma', $charisma);
                // RoomsDrivers::where('driver_id', $to_user->id)->first()->increment('charisma', $charisma);
                RoomsDrivers::where('driver_id', $to_user->id)->latest()->increment('charisma', $charisma);

                $room = Room::find($request->room_id);

                if ($to_user->hasRole('streamer')) {
                    $user_streamer = $to_user->currentStreamerAdminsPivot;

                    if($user_streamer) {
                        $info = $user_streamer->streamerInfo()->where('mins', 0)->latest()->first();
                        $info ? $info->increment('gold', $gift->price) : null;
                    }
                }

                if ($room->host_id != $to_user->id && $room->host->hasRole('streamer')) {
                    $user_streamer = $room->host->currentStreamerAdminsPivot;

                    if ($user_streamer) {
                        $bonus = $gift->price * config('streamers.host_bonus_percent', 0);
                        $info = $user_streamer->streamerInfo()->where('mins', 0)->latest()->first();
                        $info ? $info->increment('gold', $bonus) : null;
                    }
                }

                broadcast(new GiftSent($room, $gift, $user, $to_user));

                if($gift->price >= Settings::getValue('view_banner_price')) {
                    JobsExpensiveGift::dispatch($gift, $user, $to_user);
                }
                
                // $room->broadcastCharismaCounter();
                // broadcast(new CharismaCounter($room));
            }, 5);

            return response()->json([
                'status_code' => 200,
                'message'     => 'success',
                'diamonds'    => $user->diamonds,
                'user'        => $user,
                'to_user'     => $to_user,
                'gift'        => $gift,
            ]);

        } catch (\Exception $ex) {
            // MYSQL out of range exception on diamonds column (negative value)
            if($ex instanceof \PDOException && $ex->getCode() == 22003){
                return response()->json([
                    'status_code' => 409,
                    'message' => 'Insufficient Diamonds Credit',
                ]);
            }
            return $this->internalServerError($ex);
        }
    }








}
