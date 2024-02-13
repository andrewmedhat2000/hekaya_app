<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\BannedUser;
use App\Models\User;

class BannedUsers
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

        //If user Authenticated
        if(auth()->user()){
            $user = auth()->user();

        }elseif($request->social_id){
            //If user not Authenticated
            $user = User::where('social_id', $request->social_id)->first();
            if ($user == null) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'Wrong credentials',
                ], 422);
            }

        }elseif($request->user_name){
            //If user not Authenticated
            $user = User::where('user_name', $request->user_name)->first();
            if ($user == null) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'Wrong credentials',
                ], 422);
            }
        }else{
            $user = null;
        }



        //check if user is banned
        $bannedUser = BannedUser::where('user_id', $user->id)->first();
        if ($bannedUser) {
            if ($bannedUser->end_time > Carbon::now()) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'This Mobile Device is banned.',
                ], 422);
            } else {
                $bannedUser->delete();
            }
        }


        //check if mobile_uuid is banned
        if ($user AND $user->mobile_uuid) {
            $bannedMobile = BannedUser::where('mobile_uuid', $user->mobile_uuid)->first();
            if ($bannedMobile) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'This Mobile Device was banned.',
                ], 422);
            }
        }
        if ($request->mobile_uuid) {
            $bannedMobile = BannedUser::where('mobile_uuid', $request->mobile_uuid)->first();
            if ($bannedMobile) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'This Mobile Device was banned.',
                ], 422);
            }
        }

        //check if fcm_key is banned
        if ($user AND $user->fcm_key) {
            $bannedfcm = BannedUser::where('fcm_key', $user->fcm_key)->first();
            if($bannedfcm) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'This Mobile Device was banned.',
                ], 422);
            }
        }
        if ($request->fcm_key) {
            $bannedfcm = BannedUser::where('fcm_key', $request->fcm_key)->first();
            if($bannedfcm) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'This Mobile Device was banned.',
                ], 422);
            }
        }


        //check if social_id is banned
        if ($user AND $user->social_id) {
            $bannedSocial = BannedUser::where('social_id', $user->social_id)->first();
            if ($bannedSocial) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'This Mobile Device was banned.',
                ], 422);
            }
        }
        if ($request->social_id) {
            $bannedSocial = BannedUser::where('social_id', $request->social_id)->first();
            if ($bannedSocial) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'This Mobile Device was banned.',
                ], 422);
            }
        }


        if (!$user->active) {
            if ($user->active_end_time > Carbon::now()) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'This User is deactivated by admin.',
                ], 422);
            } else {
                $user->active = 1;
                $user->active_end_time = null;
                $user->save();
            }
        }

        return $next($request);
    }
}
