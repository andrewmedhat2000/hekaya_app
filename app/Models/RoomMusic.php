<?php

namespace App\Models;

use App\Classes\AgoraDynamicKey\RtcTokenBuilder;
use App\Jobs\BroadcastCharismaCounter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class RoomMusic extends Model
{
    protected $table = 'room_musics';

    protected $fillable = [
        'room_id', 'music', 'name', 'is_active', 'is_played','start_time', 'seek_to', 'created_at', 'updated_at'
    ];

    protected $appends = [
        'music_url',
    ];

    public $upload_distination = '/uploads/rooms/music/';

    public function setMusicAttribute($value)
    {
        if (!$value instanceof UploadedFile) {
            $this->attributes['music'] = $value;
            return;
        }
        $music_name = str_random(60);
        $music_name = $music_name . '.' . $value->getClientOriginalExtension(); // add the extention
        $value->move(public_path($this->upload_distination), $music_name);
        $this->attributes['music'] = $music_name;

        $room_id = $this->attributes['room_id'];
        $music_time =  (int) Carbon::now()->valueOf();

    }
    public function getMusicUrlAttribute()
    {
        if (!$this->music) {
            return null;
        }
        return strpos($this->music, '/') !== false ? $this->music : asset($this->upload_distination . $this->music);
    }


    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

}
