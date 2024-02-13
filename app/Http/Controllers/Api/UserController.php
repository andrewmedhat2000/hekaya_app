<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserFriendsResponse;
use App\Http\Resources\UserFullResponse;
use App\Http\Resources\UserResourceCollection;
use App\Models\Files;
use App\Models\GiftUserHistory;
use App\Models\LocalChannel;
use App\Models\LocalChat;
use App\Models\User;
use App\Models\UserBlocks;
use App\Models\UserFriendRequests;
use App\Models\UserFriends;
use App\Models\UserStreamerInfo;
use App\Models\UserVerificationCodes;
use App\Notifications\UserRegisterNotification;
use App\Notifications\UserUpdateNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\DeletionRequest;
use App\Models\Report;

class UserController extends BaseController
{
    //
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {


            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'email'    => 'email|unique:users,email,' . $user->id,
                'user_name'    => 'unique:users,user_name,' . $user->id,
                'name'          => 'nullable|string',
                'phone'          => 'unique:users,phone,' . $user->id,
                'profile_pic'   => 'image',
            ]);

            if ($validator->fails() != null) {
                return $this->apiErrorResponse($validator->errors()->first(), 409);
            }

            $input = $request->only([
                'email', 'user_name', 'name', 'phone', 'bio', 'description', 'age', 'gender', 'profile_pic', 'country_flag'
            ]);
            //        dd(auth()->user()->id);

            $old_user = User::where('id', $user->id)->first();

            // // if client change and update his number he must verify it again
            // if ($request->get('email') !== $old_user->email && !empty($request->get('email'))) {
            //     $old_user->email_verified_at = null;

            //     //create verification code
            //     $data['code'] = mt_rand(10000, 99999);
            //     $data['expire_at'] = Carbon::now()->addDays(1)->toDateTimeString();
            //     $data['user_id'] = $old_user->id;
            //     $verification_code = UserVerificationCodes::create($data);
            //     $old_user->email = $request->get('email');
            //     $old_user->save();

            //     $old_user->notify(new UserUpdateNotification($verification_code));
            // }

            $old_user->update($input);

            $output = User::where('id', $user->id)->first();

            //$token = $this->generateNewToken($output,1);
            DB::commit();


            return response()->json([
                'status_code' => 200,
                'message' => 'Updated successfully.',
                'user' => (new UserFullResponse($output))
            ], 200);

            return $this->apiResponse(200, 'Updated successfully.', 'user', (new UserFullResponse($output)));
        } catch (\Exception $ex) {

            DB::rollback();
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }


    public function profileDetails(Request $request)
    {
        try {

            $self = Auth::user();

            $user = $request->input('id') == $self->id
                ? $self
                : User::where('id', $request->get('id'))->first();

            if (!$user) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }


            if($user->id != $self->id) {

                if ($user->friendBlocks->contains($self->id)) {
                    return response()->json([
                        'status_code' => 409,
                        'message' => 'You cannot see this profile. The User ' . $user->user_name . ' has blocked you.',
                    ], 409);
                }

                if ($self->friendBlocks->contains($user->id)) {
                    $user->is_friend = 2;
                } elseif ($self->friends->contains($user->id)) {
                    $user->is_friend = 1;
                }
            }


            return response()->json([
                'status_code' => 200,
                'user' => (new UserFullResponse($user))
            ], 200);
        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function uploadImage(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'file'          => 'required|file|max:100000',
            ]);


            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'error_message' => $validator->errors()->first(),
                ], 409);
            }


            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'status_code' => 404,
                    'message' => "Not Authorized.",
                ], 404);
            }

            $data['file'] = $request->file('file');
            $data['user_id'] = $user->id;

            DB::beginTransaction();

            $createFile = Files::create($data);
            DB::commit();


            return response()->json([
                'status_code' => 200,
                'file_url' => $createFile->file_url,
            ], 200);
        } catch (\Exception $ex) {

            DB::rollback();

            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function createPin(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'password_current'    => 'required',
                'payment_pin'          => 'required',
            ]);

            if ($validator->fails() != null) {
                return response()->json($validator->messages());
            }

            $user = Auth::user();

            $password_current = $request->get('password_current');
            $payment_pin = $request->get('payment_pin');

            //check if the user is logged with social account so he hasn't password
            if ($user->social_id && !Hash::check($password_current, $user->password)) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'Email is registered with social account: ' . $user->social_type,
                ], 409);
            }

            if (!Hash::check($password_current, $user->password)) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'Wrong password'
                ], 409);
            }

            if ($user->payment_pin) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'User already has a pin'
                ], 409);
            }


            $user->payment_pin = $payment_pin;
            $user->save();

            // $output = User::where('id', $user->id)->first();

            return response()->json([
                'status_code' => 200,
                'message' => 'Pin Created successfully.',
            ], 200);
        } catch (\Exception $ex) {

            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function changePin(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'password_current'    => 'required',
                'payment_pin_current'          => 'required',
                'payment_pin_new'          => 'required',

            ]);

            if ($validator->fails() != null) {
                return response()->json($validator->messages());
            }

            $user = Auth::user();

            $password_current = $request->get('password_current');
            $payment_pin_current = $request->get('payment_pin_current');
            $payment_pin_new = $request->get('payment_pin_new');

            //check if the user is logged with social account so he hasn't password
            if ($user->social_id && !Hash::check($password_current, $user->password)) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'Email is registered with social account: ' . $user->social_type,
                ], 409);
            }

            if (!Hash::check($password_current, $user->password)) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'Wrong password'
                ], 409);
            }


            if ($payment_pin_current === $payment_pin_new) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'New pin and current pin are identical'
                ], 409);
            }


            if (!Hash::check($payment_pin_current, $user->payment_pin)) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'Wrong current pin'
                ], 409);
            }

            $user->payment_pin = $payment_pin_new;
            $user->save();

            // $output = User::where('id', $user->id)->first();

            return response()->json([
                'status_code' => 200,
                'message' => 'Pin Changed successfully.',
            ], 200);
        } catch (\Exception $ex) {

            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }



    public function showUserDetails(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id'         => 'required|exists:users,id',
            ]);


            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }

            $user = Auth::user();
            $user_profile = User::where('id', $request->get('user_id'))->first();

            if (!$user_profile) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'User not found.'
                ], 404);
            }


            if($user_profile->id != $user->id) {

                if ($user_profile->friendBlocks->contains($user->id)) {
                    return response()->json([
                        'status_code' => 409,
                        'message' => 'You cannot see this profile. The User ' . $user_profile->user_name . ' has blocked you.',
                    ], 409);
                }

                if ($user->friendBlocks->contains($user_profile->id)) {
                    $user_profile->is_friend = 2;
                } elseif ($user->friends->contains($user_profile->id)) {
                    $user_profile->is_friend = 1;
                }
            }

            // $is_friend = 0;
            // if ($user_profile->friends->contains($user->id)) {
            //     $is_friend = 1;
            // }
            // if ($user_profile->friendBlocks->contains($user->id)) {
            //     $is_friend = 2;
            // }
            // $user_profile->is_friend = $is_friend;


            $is_follower = 0;
            if ($user->myFollowers->contains($user_profile->id)) {
                $is_follower = 1;
            }

            $user_profile->is_follower = $is_follower;

            $am_following = 0;
            if ($user_profile->myFollowers->contains($user->id)) {
                $am_following = 1;
            }

            $user_profile->am_following = $am_following;

            $is_favorite = 0;
            if ($user->favorites->contains($user_profile->id)) {
                $is_favorite = 1;
            }

            $user_profile->is_favorite = $is_favorite;


            $has_friend_request = 0;
            if ($user_profile->myFriendRequests->contains($user->id)) {
                $has_friend_request = 1;
            }

            if ($user->myFriendRequests->contains($user_profile->id)) {
                $has_friend_request = 2;
            }

            $user_profile->has_friend_request = $has_friend_request;

            return response()->json([
                'status_code' => 200,
                'user' => (new UserFriendsResponse($user_profile))
            ], 200);
        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }





    public function searchUsers(Request $request)
    {
        try {

            $user = Auth::user();

            if (!$user) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $user_name = $request->get('user_name');
            $name = $request->get('name');
            $users = User::where('id', '=', $name)->get();
            if($users->count() == 0){
                $users = User::where('id', '!=', $user->id)
                ->when(!empty($user_name), function ($query) use ($user_name) {
                    $query->where('user_name', 'like', '%' . $user_name . '%');
                })->when(!empty($name), function ($query) use ($name) {
                    $query->where('name', 'like', '%' . $name . '%');
                })->get();
            }


            $blocking_list = $user->usersBlockedMe()->get();

            $users = $users->diff($blocking_list);

            return response()->json([
                'status_code' => 200,
                'user' => (new UserResourceCollection($users))
            ], 200);
        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function followUser(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'follower_id'         => 'required|exists:users,id',
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
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $want_to_follow = User::where('id', $request->get('follower_id'))->first();

            if (!$want_to_follow) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'Follower not found.'
                ], 404);
            }

            if ($user->id == $want_to_follow->id) {

                return response()->json([
                    'status_code' => 409,
                    'message' => 'You can not follow yourself.'
                ], 409);
            }

            if ($want_to_follow->myFollowers->contains($user->id)) {

                return response()->json([
                    'status_code' => 409,
                    'message' => 'You are already follow ' . $want_to_follow->user_name,
                ], 409);
            }


            DB::beginTransaction();

            $want_to_follow->myFollowers()->attach($user);
            $want_to_follow->followers += 1;
            $user->following += 1;

            $want_to_follow->save();
            $user->save();

            DB::commit();

            return response()->json([
                'status_code' => 200,
                'message' => 'Done, Now you are following ' . $want_to_follow->user_name,
            ], 200);
        } catch (\Exception $ex) {
            DB::rollback();
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function unFollowUser(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'follower_id'         => 'required|exists:users,id',
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
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $follower = User::where('id', $request->get('follower_id'))->first();

            if (!$follower) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'Follower not found.'
                ], 404);
            }

            if ($user->id == $follower->id) {

                return response()->json([
                    'status_code' => 409,
                    'message' => 'You can not un follow yourself.'
                ], 409);
            }


            if (!$follower->myFollowers->contains($user->id)) {

                return response()->json([
                    'status_code' => 409,
                    'message' => 'You can not un follow. You are not a follower.'
                ], 409);
            }

            DB::beginTransaction();

            $follower->myFollowers()->detach($user->id);

            $follower->followers -= 1;
            $user->following -= 1;

            $follower->save();
            $user->save();

            DB::commit();

            return response()->json([
                'status_code' => 200,
                'message' => 'Done, you un followed ' . $follower->user_name,
            ], 200);
        } catch (\Exception $ex) {
            DB::rollback();
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }


    public function getMyFollowers(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id'         => 'exists:users,id',
            ]);


            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }


            $user = Auth::user();

            if ($request->get('user_id')) {
                $user = User::where('id', $request->get('user_id'))->first();
            }

            if (!$user) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $followers = $user->myFollowers()->get();

            return response()->json([
                'status_code' => 200,
                'followers' => (new UserResourceCollection($followers))
            ], 200);
        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function getFollowing(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id'         => 'exists:users,id',
            ]);


            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }


            $user = Auth::user();

            if ($request->get('user_id')) {
                $user = User::where('id', $request->get('user_id'))->first();
            }


            if (!$user) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $followers = $user->amFollow()->get();


            return response()->json([
                'status_code' => 200,
                'following' => (new UserResourceCollection($followers))
            ], 200);
        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }






    public function addFavorite(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'favorite_id'         => 'required|exists:users,id',
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
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $favorite_user = User::where('id', $request->get('favorite_id'))->first();

            if (!$favorite_user) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'Favorite user not found.'
                ], 404);
            }

            if ($user->id == $favorite_user->id) {

                return response()->json([
                    'status_code' => 409,
                    'message' => 'You can not add yourself to favorites.'
                ], 409);
            }

            if ($user->favorites->contains($favorite_user)) {

                return response()->json([
                    'status_code' => 409,
                    'message' => $favorite_user->user_name . ' is already a favorite',
                ], 409);
            }

            // DB::beginTransaction();

            $user->favorites()->attach($favorite_user);
            // $favorite_user->followers += 1;
            // $user->following += 1;

            // $favorite_user->save();
            // $user->save();

            // DB::commit();

            return response()->json([
                'status_code' => 200,
                'message' => 'Done, ' . $favorite_user->user_name . ' added to favorites.',
            ], 200);
        } catch (\Exception $ex) {
            // DB::rollback();
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function removeFavorite(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'favorite_id'         => 'required|exists:users,id',
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
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $favorite_user = User::where('id', $request->get('favorite_id'))->first();

            if (!$favorite_user) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'Favorite user not found.'
                ], 404);
            }

            if ($user->id == $favorite_user->id) {

                return response()->json([
                    'status_code' => 409,
                    'message' => 'You can not remove yourself from favorites.'
                ], 409);
            }


            if (!$user->favorites->contains($favorite_user->id)) {

                return response()->json([
                    'status_code' => 409,
                    'message' => 'User is not in your favorites.'
                ], 409);
            }

            //DB::beginTransaction();

            $user->favorites()->detach($favorite_user->id);

            //$follower->followers -= 1;
            //$user->following -= 1;

            //$follower->save();
            //$user->save();

            //DB::commit();

            return response()->json([
                'status_code' => 200,
                'message' => 'Done, ' . $favorite_user->user_name . ' hase been removed from favorites.',
            ], 200);
        } catch (\Exception $ex) {
            //DB::rollback();
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function getFavorites(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id'         => 'exists:users,id',
            ]);


            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }


            $user = Auth::user();

            if ($request->get('user_id')) {
                $user = User::where('id', $request->get('user_id'))->first();
            }

            if (!$user) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $favorites = $user->favorites()->get();

            return response()->json([
                'status_code' => 200,
                'favorites' => (new UserResourceCollection($favorites))
            ], 200);
        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function getFavoriteTo(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id'         => 'exists:users,id',
            ]);


            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }


            $user = Auth::user();

            if ($request->get('user_id')) {
                $user = User::where('id', $request->get('user_id'))->first();
            }


            if (!$user) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $favoriteTo = $user->favoriteTo()->get();


            return response()->json([
                'status_code' => 200,
                'FavoriteTo' => (new UserResourceCollection($favoriteTo))
            ], 200);
        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }






    public function sendFriendRequest(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'friend_id'         => 'required|exists:users,id',
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
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $friend = User::where('id', $request->get('friend_id'))->first();

            if (!$friend) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'Friend not found.'
                ], 404);
            }

            if ($user->id == $friend->id) {

                return response()->json([
                    'status_code' => 409,
                    'message' => 'You can not send friend request to yourself.'
                ], 409);
            }

            if ($friend->myFriendRequests->contains($user->id)) {

                return response()->json([
                    'status_code' => 409,
                    'message' => 'You are already send friend request to ' . $friend->user_name,
                ], 409);
            }

            if ($user->myFriendRequests->contains($friend->id)) {

                return response()->json([
                    'status_code' => 409,
                    'message' => 'The user:' . $friend->user_name . ' already send friend request to you',
                ], 409);
            }

            if ($friend->friendBlocks->contains($user->id)) {

                return response()->json([
                    'status_code' => 409,
                    'message' => 'You can not send friend request, The User is blocked you ' . $friend->user_name,
                ], 409);
            }

            if ($friend->friends->contains($user->id)) {

                return response()->json([
                    'status_code' => 409,
                    'message' => 'You are already friend with ' . $friend->user_name,
                ], 409);
            }


            DB::beginTransaction();

            $friend->myFriendRequests()->attach($user);

            DB::commit();

            return response()->json([
                'status_code' => 200,
                'message' => 'Done, Friend request are send to ' . $friend->user_name,
            ], 200);
        } catch (\Exception $ex) {
            DB::rollback();
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function acceptFriendRequest(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'requester_id'         => 'required|exists:users,id',
            ]);
            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }


            $user = Auth::user();
            $requester = User::where('id', $request->get('requester_id'))->first();
            if (!$user) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }
            if (!$requester) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Requester not found.'
                ], 404);
            }
            if ($user->id == $requester->id) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'You can not accept friend request to yourself.'
                ], 409);
            }


            $friend_request = UserFriendRequests::where('user_id', $user->id)
                ->where('requester_id', $requester->id)->first();

            if (!$friend_request) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'There is no friend request with this user ' . $requester->user_name,
                ], 409);
            }


            DB::beginTransaction();

                $user->myFriendRequests()->detach($requester);

                $user->myFriends()->attach($requester);

                // $user->friends_count += 1;
                // $requester->friends_count += 1;

                $user->increment('friends_count', 1);
                $requester->increment('friends_count',1);

                $user->save();
                $requester->save();

            DB::commit();

            return response()->json([
                'status_code' => 200,
                'message' => 'Done,Now you are friend with ' . $requester->user_name,
            ], 200);

        } catch (\Exception $ex) {
            DB::rollback();
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function cancelFriendRequest(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'requester_id'         => 'required|exists:users,id',
            ]);
            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }


            $user = Auth::user();
            $requester = User::where('id', $request->get('requester_id'))->first();

            if (!$user) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }
            if (!$requester) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Requester not found.'
                ], 404);
            }
            if ($user->id == $requester->id) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'You can not cancel friend request to yourself.'
                ], 409);
            }

            $recieve_friend_request = UserFriendRequests::where('user_id', $user->id)
                ->where('requester_id', $requester->id)->first();

            $send_friend_request = UserFriendRequests::where('requester_id', $user->id)
                ->where('user_id', $requester->id)->first();

            if (!$send_friend_request && !$recieve_friend_request) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'There is no friend request with this user ' . $requester->user_name,
                ], 409);
            }


            DB::beginTransaction();

                if ($recieve_friend_request) {
                    $user->myFriendRequests()->detach($requester);

                    DB::commit();

                    return response()->json([
                        'status_code' => 200,
                        'message' => 'Done,friend request with ' . $requester->user_name . ' is cancelled',
                    ], 200);
                } else {
                    $requester->myFriendRequests()->detach($user);

                    DB::commit();

                    return response()->json([
                        'status_code' => 200,
                        'message' => 'Done,friend request with ' . $user->user_name . ' is cancelled',
                    ], 200);
                }
        } catch (\Exception $ex) {
            DB::rollback();
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function unFriendUser(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'friend_id'         => 'required|exists:users,id',
            ]);
            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }


            $user = Auth::user();
            $firend_id = $request->get('friend_id');
            $friend = User::where('id', $firend_id)->first();

            if (!$user) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }
            if (!$friend) {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Friend not found.'
                ], 404);
            }
            if ($user->id == $friend->id) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'You can not un friend yourself.'
                ], 409);
            }


            // Get friendship if not found check maybe the friend who is intiate the friendship
            $friendship = UserFriends::where('user_id', $user->id)->where('friend_id', $firend_id)->first();
            if (!$friendship) {
                $friendship = UserFriends::where('friend_id', $user->id)->where('friend_id', $user->id)->first();
            }
            if (!$friendship) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'There is no friendship with this user ' . $friend->user_name,
                ], 409);
            }

            DB::beginTransaction();

                $friendship->delete();

                $user->decrement('friends_count', 1);
                $friend->decrement('friends_count',1);

            DB::commit();

            return response()->json([
                'status_code' => 200,
                'message' => 'Done,Now you are not friend with ' . $friend->user_name . 'anymore',
            ], 200);

        } catch (\Exception $ex) {
            DB::rollback();
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function blockUser(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'friend_id'         => 'required|exists:users,id',
            ]);


            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }

            /** @var User $user */
            $user = Auth::user();

            if (!$user) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $friend = User::where('id', $request->get('friend_id'))->first();

            if (!$friend) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'Blocked User not found.'
                ], 404);
            }

            if ($user->id == $friend->id) {

                return response()->json([
                    'status_code' => 409,
                    'message' => 'You can not block yourself.'
                ], 409);
            }

            DB::beginTransaction();

            // get friendship if not found check maybe the friend who is intiate the friendship

            $friendship = UserFriends::where('user_id', $user->id)
                ->where('friend_id', $friend->id)->first();

            if (!$friendship) {

                $friendship = UserFriends::where('user_id', $friend->id)
                    ->where('friend_id', $user->id)->first();
            }

            if ($friendship) {
                $friendship->delete();

                $user->friends_count -= 1;
                $friend->friends_count -= 1;

                $user->save();
                $friend->save();
            }

            $user->friendBlocks()->syncWithoutDetaching($friend);

            DB::commit();

            return response()->json([
                'status_code' => 200,
                'message' => 'Done,you have blocked ' . $friend->user_name,
            ], 200);
        } catch (\Exception $ex) {
            DB::rollback();
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }


    public function unblockUser(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'blocked_id'         => 'required|exists:users,id',
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
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $blocked = User::where('id', $request->get('blocked_id'))->first();

            if (!$blocked) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'blocked user not found.'
                ], 404);
            }

            if ($user->id == $blocked->id) {

                return response()->json([
                    'status_code' => 409,
                    'message' => 'You can not block yourself.'
                ], 409);
            }


            $blockedship = UserBlocks::where('user_id', $user->id)
                ->where('blocked_id', $blocked->id)->first();


            if (!$blockedship) {
                return response()->json([
                    'status_code' => 409,
                    'message' => 'There is no blocking for this user ' . $blocked->user_name,
                ], 409);
            }

            DB::beginTransaction();

            $blockedship->delete();

            DB::commit();

            return response()->json([
                'status_code' => 200,
                'message' => 'Done,you have un block ' . $blocked->user_name,
            ], 200);
        } catch (\Exception $ex) {
            DB::rollback();
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }


    public function getMyFriends(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id'         => 'exists:users,id',
            ]);


            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }


            $user = Auth::user();

            if ($request->get('user_id')) {
                $user = User::where('id', $request->get('user_id'))->first();
            }

            if (!$user) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $freinds = $user->friends;

            return response()->json([
                'status_code' => 200,
                'friends' => (new UserResourceCollection($freinds))
            ], 200);
        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function getMyFriendRequests(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id'         => 'exists:users,id',
            ]);


            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }


            $user = Auth::user();

            if ($request->get('user_id')) {
                $user = User::where('id', $request->get('user_id'))->first();
            }

            if (!$user) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $freinds = $user->myFriendRequests;

            return response()->json([
                'status_code' => 200,
                'friend_requests' => (new UserResourceCollection($freinds))
            ], 200);
        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }









    public function getMyBlocking(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id'         => 'exists:users,id',
            ]);


            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }


            $user = Auth::user();

            if ($request->get('user_id')) {
                $user = User::where('id', $request->get('user_id'))->first();
            }


            if (!$user) {

                return response()->json([
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }

            $blockers = $user->friendBlocks()->get();


            return response()->json([
                'status_code' => 200,
                'blocking' => (new UserResourceCollection($blockers))
            ], 200);
        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }


    public function sendMessageToAdmin(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'message'         => 'string|required',
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
                    'status_code' => 404,
                    'message' => 'Entity not found.'
                ], 404);
            }


            DB::beginTransaction();

            $channel = LocalChannel::updateOrCreate([
                'name' => $user->user_name,
                'status' => 1,
            ]);


            $message = LocalChat::create([
                'from_user_id' => $user->id,
                'message' => $request->get('message'),
                'channel_id' => $channel->id,
            ]);

            DB::commit();

            return response()->json([
                'status_code' => 200,
                'message' => 'Done, Your Message are send to the admin ',
            ], 200);
        } catch (\Exception $ex) {
            DB::rollback();
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);


            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }

    public function deleteData(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'signed_request'           => 'required|regex:/.+\..+/'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()],400);
            }

            $signed_request = $request->input('signed_request');

            list($encoded_sig, $payload) = explode('.', $signed_request, 2);

            $secret = config('services.facebook.app_secret'); // Use your app secret here

            // decode the data
            $sig = base64_decode(strtr($encoded_sig, '-_', '+/'));

            $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

            // confirm the signature
            $expected_sig = hash_hmac('sha256', $payload, $secret, true);
            if ($sig !== $expected_sig) {
                return response()->json(['message' => 'Bad Signed JSON signature!'],400);
            }

            $user_id = $data['user_id'];

            $user = User::where('social_id', $user_id)->first();

            if(!$user){
                $deletion = DeletionRequest::create(['code' => Str::random(10), 'status' => 1]);
            }elseif($user->delete()){
                $deletion = DeletionRequest::create(['code' => Str::random(10), 'status' => 1]);
            }else{
                $deletion = DeletionRequest::create(['code' => Str::random(10), 'status' => 0]);
            }

            $status_url = url('/deletion?code=').$deletion->code; // URL to track the deletion
            $confirmation_code = $deletion->code; // unique code for the deletion request

            $data = array(
                'url' => $status_url,
                'confirmation_code' => $confirmation_code
            );

            return response()->json($data);
        }catch(\Exception $e){
            Log::info('exception: ');
            Log::info($e->getMessage());
            Log::info($e);

            return response()->json([
                'status_code' => 500,
                'message' => 'Something Went Wrong',
                'info' => $e->getMessage()
            ], 500);
        }
    }

    public function confirmDeletion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'           => 'required|exists:deletion_requests,code'
        ]);

        if($validator->fails()) abort(404);

        $request = DeletionRequest::where('code', $request->input('code'))->first();

        return view('deletion', compact('request'));
    }




    public function createReport(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'reported_id'         => 'required|exists:users,id',
                'message'         => 'required|string',
            ]);
            if ($validator->fails() != null) {
                return response()->json([
                    'status_code' => 409,
                    'message' => $validator->errors()->first(),
                ], 409);
            }

            $report = new Report;
            $reporter_id = Auth::user()->id;
            $reported_id = $request->reported_id;
            $last_report_exist = $report->where('reporter_id', $reporter_id)->where('reported_id', $reported_id)->first();

            if($last_report_exist){

                $expires_at = Carbon::parse($last_report_exist->created_at)->addDays(1);
                if (!now()->greaterThan($expires_at)){

                    return response()->json([
                        'status_code' => 409,
                        'message' => 'Sorry, you can report this user after 24 hours',
                    ], 409);
                }

            }elseif($report->where('reported_id', $reported_id)->count() >= 3){
                return response()->json([
                    'status_code' => 409,
                    'message' => 'Sorry, you can\'t report this user more than 3 times',
                ], 409);

            }elseif($reporter_id == $reported_id){

                return response()->json([
                    'status_code' => 409,
                    'message' => 'Sorry, you can\'t report yourself',
                ], 409);
            }

            $report->create([
                'reporter_id' => $reporter_id,
                'reported_id' => $reported_id,
                'message' => $request->message,
            ]);
            return response()->json([
                'status_code' => 200,
                'message' => 'Report created successfully',
            ], 200);


        } catch (\Exception $ex) {
            Log::info('exception: ');
            Log::info($ex->getMessage());
            Log::info($ex);

            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }


    public function inc_diamond(Request $request)
    {

        try {

            $user = User::where('id', $request->user_id)->first();

            $user->diamonds= $user->diamonds+1000;
           $user->save();
            return response()->json([
                'status_code' => 200,
                'message' => 'Diamonds increased successfully.',
                
            ], 200);

        } catch (\Exception $ex) {

            return response()->json([
                'status_code' => 409,
                'message' => 'Something Went Wrong',
                'info' => $ex->getMessage()
            ], 409);
        }
    }


}
