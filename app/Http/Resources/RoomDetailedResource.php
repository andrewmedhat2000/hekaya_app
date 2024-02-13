<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class RoomDetailedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sid' => $this->sid,
            'channel_name' => $this->channel_sid,
            'name' => $this->name,
            'type'  => $this->types[$this->type],
            'background' => $this->background_url,
            'music' => $this->music_url,
            'music_volume' => $this->music_volume,
            'music_time' => $this->music_time,
            'show_calculator' => $this->show_calculator,
            'host_id' => $this->host_id,
            'status' => $this->status,
            'members_count' => $this->members_count,
            'agora_token' => $this->when($this->agora_token, $this->agora_token),
            'host' => $this->whenLoaded('host', fn() => $this->host->unsetRelations()),
            'drivers' => $this->whenLoaded('rooms_drivers', function(){
                $mutes = $this->relationLoaded('mutes') ? $this->mutes : false;

                return $this->rooms_drivers->map(function($driver) use($mutes) {
                    $mute = $mutes ? optional($mutes->firstWhere('id', $driver->driver_id))->pivot : false;
                    return array_merge([
                        'sit_num' => $driver->sit_num,
                        'user' => $driver->driver ? new UserResource($driver->driver->setAttribute('user_name', $driver->driver->name)) : null,
                        'muted' => $mute ? $mute->muted : null,
                        'muted_by_admin' => $mute ? $mute->by_admin : 0,
                        'charisma' => $driver->charisma,
                    ]);
                });
            }),
            'blacklist' => $this->whenLoaded('blacklist', function(){
                return $this->blacklist->map(function($driver){
                    return $driver ? $driver->only('id', 'name', 'user_name', 'profile_pic_url') : null;
                });
            }),
            'active_music' => $this->whenLoaded('music_list', function(){
                return new RoomMusicResource($this->active_music->first());
            }),
        ];
    }
}
