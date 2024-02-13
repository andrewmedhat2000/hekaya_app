<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFullResponse extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'user_name' => $this->user_name,
            'account_type' => $this->account_type,
            'role' => $this->roles()->first()->name ?? 'not defined',
            'age' => $this->age,
            'level' => $this->level,
            'level_progress' => $this->getLevelProgress(),
            'diamonds' => $this->diamonds,
            // 'diamonds_per_month' => $this->getDiamindsByMonth(),
            'gems' => $this->gems,
            'charisma' => $this->charisma,
            'contribution' => $this->contribution,
            'profit' => $this->profit,
            'description' => $this->description,
            'bio' => $this->bio,
            'job' => $this->job,
            'gender' => $this->gender == 1?'Female':'Male',
            'profile_pic' => $this->profile_pic,
            'profile_pic_url' => $this->profile_pic_url,
            'active_frame' => new FrameResource($this->framesHistory->first(fn($i) => $i->pivot->is_active == 1)),
            'active_login_sign' => new FrameResource($this->loginSignsHistory->first(fn($i) => $i->pivot->is_active == 1)),
            'follwers' => $this->followers,
            'following' => $this->following,
            'friends_count' => $this->friends_count,
            'is_friend' => $this->is_friend,

        ];
    }
}
