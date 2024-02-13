<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class Channel extends Model
{
    protected $table = 'channels';

    protected $fillable = ['sid', 'name', 'host_id', 'music', 'status', 'members_count'];

    protected $appends = [
        'music_url',
    ];

    public $upload_distination = '/uploads/channels/music/';

    public $statuses = ['active', 'inactive'];

    public function host()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->hasOne(Room::class, 'channel_sid', 'sid')->orderBy('rooms.status');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, ChannelUser::class);
    }

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
    }
    public function getMusicUrlAttribute()
    {
        if (!$this->music) {
            return null;
        }
        return strpos($this->music, 'http') !== false ? $this->music : asset($this->upload_distination . $this->music);
    }

    public function addMemberWebHooks()
    {
        try{
            $client = new Client(config('services.twilio.sid'), config('services.twilio.token'));

            $client->chat->v2
                ->services(config('services.twilio.service_sid'))
                ->channels($this->sid)
                ->webhooks
                ->create('webhook', [
                    'configurationUrl' => route('channels.members.webhook'),
                    'configurationFilters' => ['onMemberAdded', 'onMemberRemoved']
                ]);

            return true;

        }catch(TwilioException $ex){
            info('error while adding channel webhook:');
            info('channel_id: '. $this->id);
            info($ex->getMessage());

            return false;
        }
    }
}
