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

class LoginSignController extends BaseController
{

    public function getLoginSigns(Request $request)
    {
        try {

            $user = Auth::user();


            if (!$user) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'Wrong credentials',
                ]);
            }

            $login_signs = LoginSign::all();

            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                'login_signs' => $login_signs,
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


    public function buyLoginSign(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'loginsign_id'         => 'required|exists:login_signs,id',
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


            $login_sign = LoginSign::where('id', $request->get('loginsign_id'))->first();

            if (!$login_sign) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Login Sign Not Found',
                ], 404);
            }

            $login_signs = $user->loginSignsHistory()->wherePivot('status', 0)->get();

            if ($login_signs->contains($login_sign)) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'You already bought this Login Sign',
                ], 404);
            }

            if ($user->diamonds >= $login_sign->price) {
                DB::beginTransaction();

                $login_sign_bought = $user->loginSignsHistory()->attach($login_sign);
                $user->diamonds -= $login_sign->price;

                $ratio = CurrencyWorth::first();
                if(!$ratio) $ratio = CurrencyWorth::create(['diamond' => 1, 'gem' => 1, 'charisma' => 1,  'contribution' => 1]);
                $charisma_factor = $ratio->charisma / $ratio->diamond;
                $user->charisma += $login_sign->price * $charisma_factor;

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

    public function getMyLoginSigns(Request $request)
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'Wrong credentials',
                ], 422);
            }

            $login_signs = $user->loginSignsHistory()->wherePivot('status', 0)->get();


            return response()->json([
                'status_code' => 200,
                'message' => 'success',
                'login_signs' => $login_signs,
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

    public function setLoginSign(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'loginsign_id'         => 'required|integer|min:0',
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

            $loginsigns = $user->loginSignsHistory()->wherePivot('status', 0)->get()->pluck('id')->toArray();

            $active_loginsign = $user->loginSignsHistory()->wherePivot('is_active', 1)->first();

            if($request->input('loginsign_id') == '0' && $active_loginsign){
                $user->loginSignsHistory()->updateExistingPivot($active_loginsign, ['is_active' => null], false);

                return response()->json([
                    'status_code' => 200,
                    'message' => 'success',
                ]);
            }

            if(!in_array($request->input('loginsign_id'), $loginsigns))
            {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'You haven\'t bought this loginsign.',
                ], 422);
            }

            if($active_loginsign){
                $user->loginSignsHistory()->updateExistingPivot($active_loginsign->id, ['is_active' => 0], false);
            }

            $user->loginSignsHistory()->updateExistingPivot($request->input('loginsign_id'), ['is_active' => 1], false);

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
