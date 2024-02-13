<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmojiSends extends Model
{
    protected $table = 'emoji_users';

    protected $fillable = ['emoji_id', 'user_id','room_id', 'created_at'];

    const UPDATED_AT = null;


    public function emoji()
    {
        return $this->belongsTo(Emoji::class, 'emoji_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

}
