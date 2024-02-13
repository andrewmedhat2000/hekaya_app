<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Cache;

class RoomMusicResource extends JsonResource
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
            'name' => $this->name,
            'url' => $this->music_url,
            'is_active' => $this->is_active,
            'start_time' => $this->start_time,
            'is_played' => $this->is_played,
            'seek_to' => $this->seek_to,
            'music_volume' => $this->room->when('music_volume', function(){
                return $this->room->music_volume;
            }),
        ];
    }
}
