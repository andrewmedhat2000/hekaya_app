<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCompactResource extends JsonResource
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
            'user_name' => $this->name,
            'level' => $this->level,
            'email' => $this->email,
            'profile_pic_url' => $this->profile_pic_url,
            'active_frame' => new FrameResource($this->framesHistory->first(fn($i) => $i->pivot->is_active == 1)),
            'active_login_sign' => new FrameResource($this->loginSignsHistory->first(fn($i) => $i->pivot->is_active == 1)),
        ];
    }
}
