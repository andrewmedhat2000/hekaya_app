<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedUser extends Model
{
    //
    protected $table = 'banned_users';

    protected $fillable = [
        'mobile_uuid','user_id','end_time', 'fcm_key', 'social_id', 'email',
    ];

    protected $casts = [
        'end_time' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
