<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocalChat extends Model
{
    //
    protected $table = 'local_chat';

    protected $fillable = [
        'channel_id','from_user_id', 'message'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }
}
