<?php

namespace App\Http\Controllers\Api\Store;

use Carbon\Carbon;
use App\Models\Gift;
use App\Models\Room;
use App\Models\User;
use App\Models\Badge;
use App\Models\Frame;
use App\Events\GiftSent;
use App\Models\FrameUser;
use App\Models\GiftSends;
use App\Models\LoginSign;
use App\Models\RoomsDrivers;
use Illuminate\Http\Request;
use App\Jobs\FCMNotification;

use App\Models\CurrencyWorth;
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

use App\Models\Emoji;
use App\Models\EmojiSends;
use App\Http\Controllers\Api\BaseController;

class DiamondController extends BaseController
{

    public function getDiamondsPrice()
    {
        try {
            $price = CurrencyWorth::first();
            $user = Auth::user();

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                'price' => $price,
                'diamonds' => $user->diamonds,
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

    public function buyDiamonds(Request $request)
    {
        return response()->json([
            'status_code' => 409,
            'message' => "هذه الخدمة غير متاحة",
        ], 409);

        try {
            $validator = Validator::make($request->all(), [
                'price'         => 'required|numeric'
            ]);


            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }

            $currency_worth = CurrencyWorth::first();
            if(!$currency_worth) $currency_worth = CurrencyWorth::create(['diamond' => 1, 'gem' => 1, 'charisma' => 1,  'contribution' => 1]);

            $diamonds = ($currency_worth->diamond / $currency_worth->price) * $request->input('price');

            $user = Auth::user();

            $user->diamonds += $diamonds;

            $user->save();

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                'user' => new UserResource($user),
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

    public function getChannelDiamonds(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'channel_sid'           => 'required|exists:rooms,channel_sid'
            ]);

            if ($validator->fails()) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            $gifts = GiftSends::join('gifts', 'gift_users.gift_id', '=', 'gifts.id')
            ->select(['gift_users.*', 'gifts.price'])
            ->where('channel_sid', $request->input('channel_sid'))
            ->get()
            ->groupBy('from_user_id')
            ->map(function($group){
                $group->sum('price');
            });

            $drivers = RoomsDrivers::where('channel_sid', $request->input('channel_sid'))
            ->where('status', 'connected')
            ->with('driver')->get()
            ->map(function($driver) use($gifts){
                return [
                    'driver' => new UserResource($driver->driver),
                    'diamonds' => $gifts[$driver->driver_id] ?? 0
                ];
            });

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                'drivers' => $drivers,
            ]);

        }catch(\Exception $ex){
            return $this->internalServerError($ex);
        }

    }

    public static function disableExpiredFrames()
    {
        $frames = FrameUser::where('status', 0)->with('frame')->get();

        $today = strtotime(date('Y-m-d'));
        foreach($frames as $frame){
            if($frame->frame->days_to_expire == null) continue;
            $expires_at = strtotime($frame->bought_at." + ".$frame->frame->days_to_expire." days");
            if ($today > $expires_at){
                $frame->update(['status' => 1, 'is_active' => 0]);
            }
        }
    }

    public function convertGemsToDiamonds(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'gems'         => 'required|integer|min:1',
            ]);


            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }

            /** @var User $user */
            $user = Auth::user();

            if ($user->streamerAdminsPivot()->where('ends_at', '>', today()->addDays(-3))->exists()) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'لا يمكنك التحويل قبل نهاية مدة الوكالة',
                ], 409);
            }

            $gems = $request->input('gems');

            if ($user->gems < $gems) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'You do not have this amount of Gems',
                ], 422);
            }

            $ratio = CurrencyWorth::first();
            if(!$ratio) $ratio = CurrencyWorth::create(['diamond' => 1, 'gem' => 1, 'charisma' => 1,  'contribution' => 1]);

            $diamond_factor = $ratio->diamond / $ratio->gem;
            $user->diamonds += $gems * $diamond_factor;
            $user->gems -= $gems;
            $user->save();

            DB::commit();

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
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



}
