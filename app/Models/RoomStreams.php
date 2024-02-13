<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomStreams extends Model
{


    protected $table = 'room_streams';

    const UPDATED_AT = null;

    protected $fillable = [
        'room_id', 'media_sid', 'stream_sid'
    ];


    public function room(){

        $this->belongsTo(Room::class, 'room_id');
    }




}
