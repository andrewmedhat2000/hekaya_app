<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalChannel extends Model
{
    //

    protected $table = 'local_channels';

    protected $fillable = [
        'id','name','status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'name', 'user_name');
    }

    public function chat()
    {
        return $this->hasMany(LocalChat::class, 'channel_id', 'id');
    }
}
