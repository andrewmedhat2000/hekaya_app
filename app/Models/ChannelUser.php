<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChannelUser extends Model
{
    protected $table = 'channel_users';

    protected $fillable = [
        'channel_id',
        'user_id',
    ];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

}
