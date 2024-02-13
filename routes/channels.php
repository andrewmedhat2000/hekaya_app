<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;

Broadcast::channel('room.{id}', function () {
    return true;
});

Broadcast::channel('users.{id}', function ($user) {
    return array_merge(
        $user->only(['id', 'name', 'profile_pic_url']),
        ['active_login_sign' => optional($user->loginSignsHistory->first(fn($i) => $i->pivot->is_active == 1))->only(['id', 'name', 'image_url'])]
    );
});

Broadcast::channel('conversation.{id}', function ($user, $conversation_id) {
    return DB::table('conversation_user')->where([['user_id', $user->id],['conversation_id', $conversation_id]])->exists();
});

