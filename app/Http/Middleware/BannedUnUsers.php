<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\BannedUser;
use App\Models\User;

class BannedUnUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($request->mobile_uuid) {
            $banned_mobile_uuid = BannedUser::where('mobile_uuid', $request->mobile_uuid)->first();
            if ($banned_mobile_uuid) {
                return response()->json([
                    'status_code' => 423,
                    'message' => 'This Mobile Device was banned.',
                ], 423);
            }
        }

        if ($request->fcm_key) {
            $banned_fcm_key = BannedUser::where('fcm_key', $request->fcm_key)->first();
            if ($banned_fcm_key) {
                return response()->json([
                    'status_code' => 423,
                    'message' => 'This Mobile Device was banned.',
                ], 423);
            }
        }


        if($request->social_id){
            $user = User::where('social_id', $request->social_id)->first();
        }elseif($request->user_name){
            $user = User::where('user_name', $request->user_name)->first();
        }
        if ($user) {
            if (!$user->active) {
                if ($user->active_end_time > Carbon::now()) {
                    return response()->json([
                        'status_code' => 423,
                        'message' => 'This User is deactivated by admin.',
                    ], 423);
                } else {
                    $user->active = 1;
                    $user->active_end_time = null;
                    $user->save();
                }
            }
        }

        return $next($request);
    }
}
