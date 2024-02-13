<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'unique_id' => $this->unique_id,
            'name' => $this->name,
            'user_name' => $this->user_name,
            'account_type' => $this->account_type,
            // 'role' => $this->roles()->first()->name ?? 'not defined',
            'age' => $this->age,
            'country_flag' => $this->country_flag,
            'level' => $this->level,
            'diamonds' => $this->diamonds,
            'gems' => $this->gems,
            'charisma' => $this->charisma,
            'contribution' => $this->contribution,
            'profit' => $this->profit,
            'description' => $this->description,
            'bio' => $this->bio,
            'job' => $this->job,
            'gender' => $this->gender == 1?'Female':'Male',
            'email' => $this->email,
            'phone' => $this->phone,
            'profile_pic' => $this->profile_pic,
            'profile_pic_url' => $this->profile_pic_url,
            'active_frame' => new FrameResource($this->framesHistory->first(fn($i) => $i->pivot->is_active == 1)),
            'active_login_sign' => new FrameResource($this->loginSignsHistory->first(fn($i) => $i->pivot->is_active == 1)),

        ];
    }
}
