<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFriendsResponse extends JsonResource
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
            'age' => $this->age,
            'level' => $this->level,
            'diamonds' => $this->diamonds,
            'gems' => $this->gems,
            'charisma' => $this->charisma,
            'contribution' => $this->contribution,
            'profit' => $this->profit,
            'description' => $this->description,
            'job' => $this->job,
            'gender' => $this->gender == 1?'Female':'Male',
            'profile_pic' => $this->profile_pic,
            'profile_pic_url' => $this->profile_pic_url,
            'follwers' => $this->followers,
            'following' => $this->following,
            'friends_count' => $this->friends_count,
            'is_friend' => $this->is_friend,
            'is_follower' => $this->is_follower,
            'am_following' => $this->am_following,
            'is_favorite' => $this->is_favorite,

        ];
    }
}
