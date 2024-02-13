<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLuckyGift extends Model
{
    protected $table = 'user_lucky_gift';

    protected $fillable = ['user_id', 'gift_id'];
}
