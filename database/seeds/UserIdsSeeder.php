<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserIdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // "users" => ['id'],
        // "badges_users" => ['user_id'],
        // "banned_users" => ['user_id'],
        // "conversation_user" => ['user_id'],
        // "emoji_users" => ['user_id'],
        // "files" => ['user_id'],
        // "frames_users" => ['user_id'],
        // "gift_users" => ['user_id'],
        // "gifts_users_history" => ['from_user_id', 'to_user_id'],
        // "guess_games" => ['challenger_id', 'challenged_id'],
        // "local_chat" => ['from_user_id'],
        // "login_signs_users" => ['user_id'],
        // "merchant_transaction_ids" => ['user_id'],
        // "messages" => ['user_id'],
        // "oauth_auth_codes" => ['user_id', 'client_id'],
        // "oauth_access_tokens" => ['user_id', 'client_id'],
        // "oauth_clients" => ['user_id'],
        // "oauth_personal_access_clients" => ['client_id'],
        // "package_users" => ['user_id'],
        // "rooms" => ['host_id'],
        // "room_blacklist" => ['user_id'],
        // "room_mutes" => ['user_id'],
        // "rooms_drivers" => ['driver_id'],
        // "user_blocks" => ['user_id', 'blocked_id'],
        // "user_followers" => ['user_id', 'follower_id'],
        // "user_friend_requests" => ['user_id', 'requester_id'],
        // "user_friends" => ['user_id', 'friend_id'],
        // "users_favorites" => ['user_id', 'favorite_id'],
        // "users_streamers" => ['streamer_id', 'streamer_admin_id'],
        // "user_verification_codes" => ['user_id'],

        $id = 75189640;
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::beginTransaction();

            // DB::table('users')->update(['id' => DB::Raw("`id` + 75189640")]);
            // DB::table('badges_users')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('banned_users')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('conversation_user')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('emoji_users')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('files')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('frames_users')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('gifts_users_history')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('gift_users')->update(['from_user_id' => DB::Raw("`from_user_id` + 75189640"), 'to_user_id' => DB::Raw("`to_user_id` + 75189640")]);
            // DB::table('local_chat')->update(['from_user_id' => DB::Raw("`from_user_id` + 75189640")]);
            // DB::table('login_signs_users')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('merchant_transaction_ids')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('messages')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('oauth_auth_codes')->update(['user_id' => DB::Raw("`user_id` + 75189640"), 'client_id' => DB::Raw("`client_id` + 75189640")]);
            // DB::table('oauth_access_tokens')->update(['user_id' => DB::Raw("`user_id` + 75189640"), 'client_id' => DB::Raw("`client_id` + 75189640")]);
            // DB::table('oauth_clients')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('oauth_personal_access_clients')->update(['client_id' => DB::Raw("`client_id` + 75189640")]);


            // DB::table('package_users')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('rooms')->update(['host_id' => DB::Raw("`host_id` + 75189640")]);
            // DB::table('room_blacklist')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('rooms_drivers')->update(['driver_id' => DB::Raw("`driver_id` + 75189640")]);
            // DB::table('room_mutes')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('user_blocks')->update(['user_id' => DB::Raw("`user_id` + 75189640"), 'blocked_id' => DB::Raw("`blocked_id` + 75189640")]);
            // DB::table('user_followers')->update(['user_id' => DB::Raw("`user_id` + 75189640"), 'follower_id' => DB::Raw("`follower_id` + 75189640")]);
            // DB::table('user_friend_requests')->update(['user_id' => DB::Raw("`user_id` + 75189640"), 'requester_id' => DB::Raw("`requester_id` + 75189640")]);
            // DB::table('user_friends')->update(['user_id' => DB::Raw("`user_id` + 75189640"), 'friend_id' => DB::Raw("`friend_id` + 75189640")]);
            // DB::table('users_favorites')->update(['user_id' => DB::Raw("`user_id` + 75189640"), 'favorite_id' => DB::Raw("`favorite_id` + 75189640")]);
            // DB::table('users_streamers')->update(['streamer_id' => DB::Raw("`streamer_id` + 75189640"), 'streamer_admin_id' => DB::Raw("`streamer_admin_id` + 75189640")]);
            // DB::table('users_streamers_info')->update(['user_streamer_id' => DB::Raw("`user_streamer_id` + 75189640")]);
            // DB::table('user_verification_codes')->update(['user_id' => DB::Raw("`user_id` + 75189640")]);
            // DB::table('guess_games')->update([
            //     'challenger_id' => DB::Raw("`challenger_id` + 75189640"),
            //     'challenged_id' => DB::Raw("`challenged_id` + 75189640"),
            //     'winner_id' => DB::Raw("`winner_id` + 75189640")
            // ]);

            // DB::table('model_has_roles')->update(['model_id' => DB::Raw("`model_id` + 75189640")]);

        DB::commit();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');


    }
}
