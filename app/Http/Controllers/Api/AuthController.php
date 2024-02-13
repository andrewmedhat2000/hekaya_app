<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Alert;
use App\Http\Resources\UserResource;
use App\Models\BannedUser;
use App\Models\PasswordReset;
use App\Models\UserVerificationCodes;
use App\Notifications\PasswordResetNotification;
use App\Notifications\PasswordResetSuccess;
use App\Notifications\UserRegisterNotification;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendSMS;

class AuthController extends BaseController
{
    //
    public function register(Request $request)
    {
        try {


            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'phone'             => 'required|unique:users,phone,{$id},id,deleted_at,NULL',
                'email'             => 'email|required|unique:users,email,{$id},id,deleted_at,NULL',
                'user_name'         => 'string|required|unique:users,user_name,{$id},id,deleted_at,NULL',
                'name'              => 'required',
                'age'               => 'string',
                'gender'            => 'between:0,1',
                'password'          => 'required|min:4',
                'profile_pic'       => 'nullable|image',
                'mobile_uuid'       => 'nullable|string',
                'bio'               => 'nullable|string',
                'fcm_token'         => 'nullable|string',
                'country_flag'      => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $est = $validator->messages();
                foreach ($est->all() as $key => $as) {
                    $messages[] = $as;
                }
                return $this->apiErrorResponse($messages, 422);
            }

            //Please note that the checking if user's device is banned in BannedUsers MiddleWare

            if ($request->input('email')) {
                $emailexist = User::where('email', $request->input('email'))->withTrashed()->first();

                if ($emailexist) {

                    // if the user is soft deleted restore him
                    if ($emailexist->deleted_at != null) {
                        $emailexist->restore();
                        $emailexist->save();

                        $input = $validator->validated();

                        $emailexist->assignRole('client');
                        $emailexist->update($input);

                        try {
                            $data['code'] = mt_rand(10000, 99999);
                            $data['expire_at'] = Carbon::now()->addDays(1)->toDateTimeString();
                            $data['user_id'] = $emailexist->id;
                            $verification_code = UserVerificationCodes::create($data);
                            $emailexist->notify(new UserRegisterNotification($verification_code));

                            // $this->sendSMS('Welcome To Hekayh. Here is your verification code:' . $verification_code->code, $emailexist->phone);
                        } catch (\Exception $ex) {
                            DB::rollBack();


                            Log::info('exception: ');
                            Log::info($ex->getMessage());
                            Log::info($ex);

                            return response()->json([
                                'status_code' => 422,
                                'message' => 'Something Went Wrong',
                                'info' => $ex->getMessage()
                            ], 422);
                        }

                        $data['token'] = $emailexist->createToken('QME')->accessToken;

                        DB::commit();
                        return response()->json([
                            'status_code' => 200,
                            'user'  => new UserResource($emailexist),
                            'token' => $data['token'],
                        ]);
                    }
                }
            }

            $input = $validator->validated();

            $user = User::create($input);
            $user->assignRole('client');

            try {
                $data['code'] = mt_rand(10000, 99999);
                $data['expire_at'] = Carbon::now()->addDays(1)->toDateTimeString();
                $data['user_id'] = $user->id;
                $verification_code = UserVerificationCodes::create($data);
                // $this->sendSMS('Welcome To Hekayh. Here is your verification code:' . $verification_code->code, $user->phone);
                $user->notify(new UserRegisterNotification($verification_code));
            } catch (\Exception $ex) {
                DB::rollBack();


                Log::info('exception: ');
                Log::info($ex->getMessage());
                Log::info($ex);

                return response()->json([
                    'status_code' => 422,
                    'message' => 'Something Went Wrong',
                    'info' => $ex->getMessage()
                ], 422);
            }

            DB::commit();

            $data['token'] = $user->createToken('QME')->accessToken;

            return response()->json([
                'status_code' => 200,
                'user'  => new UserResource($user),
                'token' => $data['token'],
            ]);
        } catch (\Exception $ex) {


            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 422,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 422);
        }
    }

    public function registerSocial(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'email'             => 'email|required|unique:users,email,{$id},id,deleted_at,NULL',
                'user_name'         => 'string|required|unique:users,user_name,{$id},id,deleted_at,NULL',
                'phone'             => 'required|unique:users,phone,{$id},id,deleted_at,NULL',
                'name'              => 'required',
                'social_id'         => 'required|unique:users,social_id,{$id},id,deleted_at,NULL',
                'social_type'       => 'required',
                'age'               => 'string',
                'gender'            => 'between:0,1',
                'profile_pic'       => 'nullable|image',
                'mobile_uuid'       => 'nullable|string',
                'bio'               => 'nullable|string',
                'fcm_token'         => 'nullable|string',
                'country_flag'      => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $est = $validator->messages();
                foreach ($est->all() as $key => $as) {
                    $messages[] = $as;
                }
                return $this->apiErrorResponse($messages, 422);
            }

            $data = $validator->validated();

            //Please note that the checking if user's device is banned in BannedUsers MiddleWare


            $emailexist = User::where('email', $request->input('email'))->withTrashed()->first();
            // if ($emailexist) {

            //         return response()->json([
            //             'status_code' => 422,
            //             'message' => 'This email are already registered',
            //         ]);
            // }
            $checkUser = User::where('social_id', $data['social_id'])->where('social_type', $data['social_type'])->orwhere('email', $request->input('email'))->withTrashed()->first();
            if ($checkUser) {

                if ($checkUser->deleted_at == null) {
                    return response()->json([
                        'status_code' => 422,
                        'message' => 'These social id and social type are already registered',
                    ]);
                } else {
                    $checkUser->restore();
                    $checkUser->save();

                    $input = $validator->validated();

                    // $input['verification_pin'] = $this->generatePIN(5);

                    $checkUser->assignRole('client');
                    $checkUser->update($input);

                    $data['token'] = $checkUser->createToken('QME')->accessToken;

                    DB::commit();
                    return response()->json([
                        'status_code' => 200,
                        'user' => new UserResource($checkUser),
                        'token' => $data['token'],
                    ]);
                }
            }

            $input = $validator->validated();

            $user = User::create($input);
            $user->assignRole('client');

            DB::commit();

            $data['token'] = $user->createToken('QME')->accessToken;
            $data['name'] = $user->name;
            return response()->json([
                'status_code' => 200,
                'user'  => new UserResource($user),
                'token' => $data['token'],
            ]);
        } catch (\Exception $ex) {


            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 422,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 422);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_name'         => 'required',
                'password'          => 'required',
            ]);

            if ($validator->fails()) {
                $est = $validator->messages();
                foreach ($est->all() as $key => $as) {
                    $messages[] = $as;
                }
                return $this->apiErrorResponse($messages, 422);
            }

            $credentials = $request->only('user_name', 'password');

            if (!auth()->attempt($credentials)) {

                return response()->json([
                    'status_code' => 422,
                    'message' => 'Wrong credentials',
                ]);
            }



            $user = Auth::user();


            // if (!$user->email_verified_at) {
            //     return response()->json([
            //         'status_code' => 401,
            //         'message' => 'User contact information not verified.',
            //     ], 401);
            // }

            if($request->has('fcm_key')){
                $user->update(['fcm_key' => $request->input('fcm_key')]);
            }
            if($request->has('mobile_uuid')){
                $user->update(['mobile_uuid' => $request->input('mobile_uuid')]);
            }


            $user_cred = new UserResource($user);

            $token = $this->generateNewToken($user, 1);

            return response()->json([
                'status_code' => 200,
                'message' => 'Login successfully .',
                'user' => $user_cred,
                'token' => $token,
            ]);
        } catch (\Exception $ex) {

            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 422,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 422);
        }
    }

    public function loginSocial(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'social_id'         => 'required',
                'social_type'          => 'required',
            ]);


            if ($validator->fails()) {
                $est = $validator->messages();
                foreach ($est->all() as $key => $as) {
                    $messages[] = $as;
                }
                return $this->apiErrorResponse($messages, 422);
            }

            $credentials = $request->only('social_id', 'social_type');

            $user = User::where($credentials)->first();

            if (!$user) {
                return response()->json([
                    'status_code' => 422,
                    'message' => 'Wrong credentials',
                ]);
            }

            //Please note that the checking if user's device is banned in BannedUsers MiddleWare


            // if (!$user->email_verified_at) {
            //     return response()->json([
            //         'status_code' => 401,
            //         'message' => 'User contact information not verified.',
            //     ], 401);
            // }

            if($request->has('fcm_key')){
                $user->update(['fcm_key' => $request->input('fcm_key')]);
            }
            if($request->has('mobile_uuid')){
                $user->update(['mobile_uuid' => $request->input('mobile_uuid')]);
            }


            $user_cred = (new UserResource($user));

            $token = $this->generateNewToken($user, 1);

            return response()->json([
                'status_code' => 200,
                'message' => 'Login successfully .',
                'user' => $user_cred,
                'token' => $token,
            ]);
        } catch (\Exception $ex) {


            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 422,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 422);
        }
    }


    protected function generateNewToken($user, $delete)
    {
        $app_name = 'QME';

        if ($delete) {
            $user->tokens()->delete();
        }

        $token = $user->createToken($app_name)->accessToken;
        return $token;
    }

    public function resetPassword_old(Request $request)
    {
        $email = $request->get('email');
        if (!$email) {
            return $this->apiErrorResponse("email field required", 422);
        }
        $user = User::where('email', $email)->first();
        if (!$user) {
            return $this->apiErrorResponse("there is no user with this email.", 404);
        }

        $pssword = str_random(10);
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => $pssword
            ]
        );


        if ($user && $passwordReset) {
            try {
                $user->notify(new PasswordResetNotification($passwordReset->token));
                // $this->sendSMS('Welcome To Hekayh. Here is your reset token:' . $passwordReset->token, $user->phone);

            } catch (\Exception $e) {
                DB::rollBack();
                return $this->apiErrorResponse("Something went wrong.", 500);
            }

            return response()->json([
                'message' => 'We have send an email to your email with your password reset!'
            ]);
        }


        return $this->apiResponse([], 'success');
    }


    public function resetPassword(Request $request)
    {
        DB::beginTransaction();

        $validator = Validator::make($request->all(), [
            'phone'         => 'regex:/^[+]{1}[0-9]{10,15}$/',
        ],[
            'regex' => 'Invalid phone number, example: +996501234567'
        ]);

        if ($validator->fails()) {
            return $this->apiErrorResponse(array_values($validator->messages()->all()), 422);
        }

        $phone = $request->phone;
        $user_phone_exist = User::where('phone', $phone)->first();

        if ($user_phone_exist) {
            try {
                $code = mt_rand(10000, 99999);
                PasswordReset::updateOrCreate([
                    'phone' => $user_phone_exist->phone,
                ],[
                    'token' => $code
                ]);

                $this->sendSMS('Welcome To Hekayh. Here is your reset token: ' . $code, $phone);

                DB::commit();
                return response()->json([
                    'message' => 'We have sent an sms to your phone with code to reset password!'
                ]);
            } catch (\Exception $e) {

                DB::rollBack();
                info('exception: ');
                info($e->getMessage());
                return $this->apiErrorResponse("Something went", 500);
            }

        }else{
            return $this->apiErrorResponse("There aren't user belongs to this phone.", 404);
        }


    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|exists:users,phone',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'

        ]);

        if ($validator->fails()) {
            return $this->apiErrorResponse(array_values($validator->messages()->all()), 422);
        }

        $user = User::where('phone', $request->get('phone'))->first();

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['phone', $user->phone]
        ])->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }

        $user->update($request->all());
        $passwordReset->delete();

        return response()->json(new UserResource($user));
    }

    public function verify(Request $request)
    {
        $code = $request->get('code');
        if (!$code) {
            return $this->apiErrorResponse("verification code required", 422);
        }

        $verification_code = UserVerificationCodes::where('code', $code)->orderBy('id', 'DESC')->first();
        if (!$verification_code) {
            return $this->apiErrorResponse("Not found.", 404);
        }

        $now = Carbon::now();
        if ($now->diffInMinutes($verification_code->expire_at, false) < 1) {
            $verification_code->delete();
            return $this->apiErrorResponse("Verification code expired.", 422);
        }

        $user = $verification_code->user;
        $user->email_verified_at = $now->toDateTimeString();
        $user->save();

        return $this->apiResponse([], 'success');
    }

    public function register_V1(Request $request)
    {
        try {

            // if ($request->input('email')) {
            //     $checkifexist = User::where('email', $request->input('email'))->first();
            //     if ($checkifexist) {
            //         if ($checkifexist->email_verified_at == null && $checkifexist->created_at->addHours(1) < Carbon::now()) {
            //             $checkifexist->delete();
            //         }
            //     }
            // }

            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'phone'             => 'required|regex:/^[+]{1}[0-9]{10,15}$/|unique:users,phone,{$id},id,deleted_at,NULL',
                'email'             => 'email|required|unique:users,email,{$id},id,deleted_at,NULL',
                'user_name'         => 'string|required|unique:users,user_name,{$id},id,deleted_at,NULL',
                'name'              => 'string',
                'age'               => 'string',
                'gender'            => 'between:0,1',
                'password'          => 'required|min:4',
                'profile_pic'       => 'nullable|image',
                'mobile_uuid'       => 'nullable|string',
                'bio'               => 'nullable|string',
                'fcm_token'         => 'nullable|string',
                'country_flag'      => 'nullable|string',
            ],
            [
                'regex' => 'invalid phone number, example: +996501234567'
            ]);

            // if ($validator->fails()) {
            //     $est = $validator->messages();
            //     foreach ($est->all() as $key => $as) {
            //         $messages[] = $as;
            //     }
            //     return $this->apiErrorResponse($messages, 422);
            // }
            if ($validator->fails() != null) {
                return $this->apiErrorResponse($validator->errors()->first());
            }

            //Please note that the checking if user's device is banned in BannedUsers MiddleWare

            if(!$request->name){
                $request->merge([
                    'name' => $request->user_name,
                ]);
            }

            if ($request->input('email')) {
                $emailexist = User::where('email', $request->input('email'))->withTrashed()->first();

                if ($emailexist) {

                    // if the user is soft deleted restore him
                    if ($emailexist->deleted_at != null) {
                        $emailexist->restore();
                        $emailexist->save();

                        $input = $validator->validated();

                        $emailexist->assignRole('client');
                        $emailexist->update($input);

                        // $this->SendVerificationCodeViaSMS($emailexist);

                        $data['token'] = $emailexist->createToken('QME')->accessToken;

                        DB::commit();
                        return response()->json([
                            'status_code' => 200,
                            'user'  => new UserResource($emailexist),
                            'token' => $data['token'],
                        ]);
                    }
                }
            }

            $input = $validator->validated();

            $user = User::create($input);
            $user->assignRole('client');

            // $this->SendVerificationCodeViaSMS($user);

            DB::commit();

            $data['token'] = $user->createToken('QME')->accessToken;

            return response()->json([
                'status_code' => 200,
                'user'  => new UserResource($user),
                'token' => $data['token'],
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function registerSocial_V1(Request $request)
    {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'email'         => 'email|required|unique:users,email,{$id},id,deleted_at,NULL',
                'user_name'     => 'string|unique:users,user_name,{$id},id,deleted_at,NULL',
                'phone'         => 'regex:/^[+]{1}[0-9]{10,15}$/|unique:users,phone,{$id},id,deleted_at,NULL',
                'name'          => 'required',
                'social_id'     => 'required|unique:users,social_id,{$id},id,deleted_at,NULL',
                'social_type'   => 'required',
                'age'           => 'nullable|integer|max:100',
                'gender'        => 'nullable|between:0,1',
                'profile_pic'   => 'nullable|image',
                'mobile_uuid'   => 'nullable|string',
                'bio'           => 'nullable|string',
                'fcm_token'     => 'nullable|string',
                'country_flag'  => 'nullable|string',
            ],
            [
                'regex' => 'invalid phone number, example: +996501234567'
            ]);

            if ($validator->fails()) {
                $est = $validator->messages();
                foreach ($est->all() as $key => $as) {
                    $messages[] = $as;
                }
                return $this->apiErrorResponse($messages, 422);
            }

            $data = $validator->validated();

            //Please note that the checking if user's device is banned in BannedUsers MiddleWare

            $checkUser = User::where('social_id', $data['social_id'])
                ->where('social_type', $data['social_type'])
                ->orWhere('email', $request->input('email'))
                ->withTrashed()->first();

            if ($checkUser) {
                if ($checkUser->deleted_at == null) {
                    return response()->json([
                        'status_code' => 422,
                        'message' => 'These social id and social type are already registered',
                    ]);
                } else {
                    $checkUser->restore();
                    $checkUser->save();

                    $input = $validator->validated();

                    // $input['verification_pin'] = $this->generatePIN(5);

                    $checkUser->assignRole('client');
                    $checkUser->update($input);

                    // $this->SendVerificationCodeViaSMS($checkUser);

                    $data['token'] = $checkUser->createToken('QME')->accessToken;

                    DB::commit();
                    return response()->json([
                        'status_code' => 200,
                        'user'  => new UserResource($checkUser),
                        'token' => $data['token'],
                    ]);
                }
            }

            $input = $validator->validated();

            $user = User::create($input);
            $user->assignRole('client');

            // $this->SendVerificationCodeViaSMS($user);

            DB::commit();

            $data['token'] = $user->createToken('QME')->accessToken;

            return response()->json([
                'status_code' => 200,
                'user'  => new UserResource($user),
                'token' => $data['token'],
            ]);

        } catch (\Exception $ex) {
            return $this->internalServerError($ex);
        }
    }

    public function resendCode(Request $request){
        $validator = Validator::make($request->all(), [
            'user_name'         => 'required_without:email|exists:users',
            'email'             => 'required_without:user_name|exists:users',
            'phone'         => 'regex:/^[+]{1}[0-9]{10,15}$/',
        ],[
            'regex' => 'invalid phone number, example: +996501234567'
        ]);
        if ($validator->fails()) {
            $est = $validator->messages();
            foreach ($est->all() as $key => $as) {
                $messages[] = $as;
            }
            return $this->apiErrorResponse($messages, 422);
        }
        if($request->has('user_name'))
        {
            $user = User::where('user_name', $request->input('user_name'))->first();
        }else{
            $user = User::where('email', $request->input('email'))->first();
        }

        if($request->has('phone'))
        {
            if($request->input('phone') == $user->phone){
                //do nothing
            }
            elseif(!User::where('phone', $request->input('phone'))->get()->isEmpty()){
                return response()->json([
                    'status_code' => 422,
                    'message' => 'this phone number is already registered to another user',
                ], 422);
            }

            $user->update(['phone' => $request->input('phone')]);
        }

        $this->SendVerificationCodeViaSMS($user);

        return $this->apiResponse([], 'success');
    }

    public function SendVerificationCodeViaSMS(User $user)
    {
        try {
            $data['code'] = mt_rand(10000, 99999);
            $data['expire_at'] = Carbon::now()->addDays(1)->toDateTimeString();
            $data['user_id'] = $user->id;
            $verification_code = UserVerificationCodes::create($data);
            // $emailexist->notify(new UserRegisterNotification($verification_code));

            // $this->sendSMS('Welcome To Hekayh. Here is your verification code:' . $verification_code->code, $user->phone);
            SendSMS::dispatchAfterResponse($verification_code->code, $user->phone);
        } catch (\Exception $ex) {
            DB::rollBack();


            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 422,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 422);
        }
    }
}
