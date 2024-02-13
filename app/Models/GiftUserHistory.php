<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftUserHistory extends Model
{
    //
    protected $table = 'gifts_users_history';

    protected $fillable = [
        'gift_id','user_id', 'status'
    ];

    public function gifts(){
        return $this->hasMany(Gift::class,'gift_id')->withTimestamps()->withPivot('status');
    }
    public function users(){
        return $this->hasMany(User::class,'user_id')->withTimestamps()->withPivot('status');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
