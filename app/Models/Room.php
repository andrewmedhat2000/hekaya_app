<?php

namespace App\Models;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\BannedUser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Jobs\BroadcastCharismaCounter;
use Illuminate\Database\Eloquent\Model;
use App\Classes\AgoraDynamicKey\RtcTokenBuilder;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = [
        'channel_sid', 'name', 'host_id', 'status', 'music', 'background', 'type', 'password', 'show_calculator', 'music_volume', 'music_time'
    ];

    public $types = [
        1 => 'public',
        2 => 'private'
    ];

    protected $appends = [
        'music_url', 'background_url'
    ];

    public $upload_distination = '/uploads/rooms/';

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

        $room_id = $this->attributes['id'];
        $music_time =  (int) Carbon::now()->valueOf();
        $this->attributes['music_time'] = $music_time;

    }
    public function getMusicUrlAttribute()
    {
        if (!$this->music) {
            return null;
        }
        return strpos($this->music, 'http') !== false ? $this->music : asset($this->upload_distination . $this->music);
    }

    public function setBackgroundAttribute($value)
    {
        if (!$value instanceof UploadedFile) {
            $this->attributes['background'] = $value;
            return;
        }
        $background_name = str_random(60);
        $background_name = $background_name . '.' . $value->getClientOriginalExtension(); // add the extention
        $value->move(public_path($this->upload_distination), $background_name);
        $this->attributes['background'] = $background_name;
    }
    public function getBackgroundUrlAttribute()
    {
        if (!$this->background) {
            return null;
        }
        return strpos($this->background, 'http') !== false ? $this->background : asset($this->upload_distination . $this->background);
    }

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    // Pivot Tabel For ManyToMany Relationship
    public function rooms_drivers()
    {
        return $this->hasMany(RoomsDrivers::class, 'room_id');
    }

    public function driversWithCharisma()
    {
        return $this->hasMany(RoomsDrivers::class, 'room_id')
            ->addSelect([
                'charisma' => GiftSends::selectRaw('sum(price) as price')
                    ->whereColumn('gift_users.room_id', 'rooms_drivers.room_id')
                    ->whereColumn('gift_users.to_user_id', 'rooms_drivers.driver_id')
                    ->whereColumn('gift_users.created_at', '>=', 'rooms_drivers.created_at')
                    ->groupBy('to_user_id')
                    ->limit(1)
            ]);
    }

    public function drivers()
    {
        return $this->belongsToMany(User::class, RoomsDrivers::class, 'room_id', 'driver_id')
            ->withPivot(['sit_num']);
    }

    public function blacklist()
    {
        return $this->belongsToMany(User::class, 'room_blacklist');
    }


    public function banlist()
    {
        return $this->hasMany(BannedUser::class, 'user_id');
    }

    public function mutes()
    {
        return $this->belongsToMany(User::class, 'room_mutes')->withPivot('muted', 'by_admin')->select('users.id');
    }

    public function streams()
    {
        return $this->hasMany(RoomStreams::class, 'room_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_sid', 'sid');
    }

    public function gifts()
    {
        return $this->hasMany(GiftSends::class);
    }

    public function setTypeAttribute($value)
    {
        $type_keys = array_flip($this->types);
        if (in_array($value, $type_keys)) {
            $this->attributes['type'] = $value;
        } elseif (in_array($value, $this->types)) {
            $this->attributes['type'] = $type_keys[$value];
        }
    }

    public function token()
    {
        $appID = config('services.agora.app_id');
        $appCertificate = config('services.agora.certificate');
        $channelName = 'room.'.$this->id;
        $user = 0;
        $role = RtcTokenBuilder::RolePublisher;
        $privilegeExpiredTs = 0;

        $token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $user, $role, $privilegeExpiredTs);

        return $token;
    }

    public function broadcastCharismaCounter()
    {
        $broadcast_at = microtime(true) + 2;
        Cache::put("ignore_charisma_jobs_before.{$this->id}", $broadcast_at);
        BroadcastCharismaCounter::dispatch($this)->delay(Carbon::createFromTimestamp($broadcast_at));
    }


    public function music_list()
    {
        return $this->hasMany(RoomMusic::class, 'room_id');
    }

    public function active_music()
    {
        return $this->hasMany(RoomMusic::class, 'room_id')->where('is_active', 1);
    }


    public static function getRoomsWithUsersCount()
    {
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            ['cluster' => config('broadcasting.connections.pusher.options.cluster')]
        );

        $channels = $pusher->get_channels(['filter_by_prefix' => 'presence-', 'info' => 'user_count'])->channels;

        $channels = collect($channels)->mapWithKeys(
            fn($ch, $k) => [substr($k, 15) => $ch->user_count]
        )->toArray();

        return $channels;
    }



}
