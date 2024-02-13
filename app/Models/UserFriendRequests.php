<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFriendRequests extends Model
{
    //
    protected $table = 'user_friend_requests';

    protected $fillable = [
        'user_id','requester_id', 'status'
    ];

}
