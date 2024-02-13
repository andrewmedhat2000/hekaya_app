<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFriends extends Model
{
    //
    protected $table = 'user_friends';

    protected $fillable = [
        'user_id','friend_id', 'status'
    ];

}
