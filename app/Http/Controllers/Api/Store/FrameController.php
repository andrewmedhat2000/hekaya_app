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

class FrameController extends BaseController
{

    public function getFrames(Request $request)
    {
        try {

            $user = Auth::user();


            if (!$user) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'Wrong credentials',
                ]);
            }

            $frames = Frame::all();

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                'frames' => $frames,
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

    public function buyFrame(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'frame_id'         => 'required|exists:frames,id',
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


            $frame = Frame::where('id', $request->get('frame_id'))->first();

            if (!$frame) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Frame Not Found',
                ], 404);
            }

            $frames = $user->framesHistory()->wherePivot('status', 0)->get();

            if ($frames->contains($frame)) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'You already bought this frame',
                ], 408);
            }

            if ($user->diamonds >= $frame->price) {
                DB::beginTransaction();

                $frame_bought = $user->framesHistory()->attach($frame, ['bought_at' => now()]);
                $user->diamonds -= $frame->price;

                $ratio = CurrencyWorth::first();
                if(!$ratio) $ratio = CurrencyWorth::create(['diamond' => 1, 'gem' => 1, 'charisma' => 1,  'contribution' => 1]);
                $charisma_factor = $ratio->charisma / $ratio->diamond;
                $user->charisma += $frame->price * $charisma_factor;

                $user->save();

                DB::commit();

                return response()->json([
                    'status_code' => 200,
                    'message' => 'success',
                ]);
            } else {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'Insufficient Diamonds Credit',
                ]);
            }
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

    public function getMyFrames(Request $request)
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'Wrong credentials',
                ], 422);
            }

            $frames = $user->framesHistory()->wherePivot('status', 0)->get();

            foreach ($frames as $frame){
                if($frame->pivot->bought_at && strtotime($frame->pivot->bought_at) > 0){
                    $frame->days_remaining = $frame->days_to_expire
                        - Carbon::parse($frame->pivot->bought_at)->diffInDays(Carbon::now());
                }else{
                    $frame->days_remaining = -1;
                }
            }

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                'frames' => $frames,
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

    public function setFrame(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'frame_id'         => 'required|integer|min:0',
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

            $frames = $user->framesHistory()->wherePivot('status', 0)->get()->pluck('id')->toArray();

            $active_frame = $user->framesHistory()->wherePivot('is_active', 1)->first();

            if($request->input('frame_id') == '0' && $active_frame){
                $user->framesHistory()->updateExistingPivot($active_frame, ['is_active' => null], false);

                return response()->json([
                    'status_code' => 200,
                    'message' => 'success',
                ]);
            }

            if(!in_array($request->input('frame_id'), $frames))
            {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'You haven\'t bought this frame.',
                ], 422);
            }

            if($active_frame){
                $user->framesHistory()->updateExistingPivot($active_frame->id, ['is_active' => 0], false);
            }

            $user->framesHistory()->updateExistingPivot($request->input('frame_id'), ['is_active' => 1], false);

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
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





}
