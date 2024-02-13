<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
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
            'user' => new UserResource($this->whenLoaded('user')),
            'last_message' => new MessageResource($this->whenLoaded('lastMessage')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
