<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessagesResource extends JsonResource
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
            
            'from' => $this->from,
            'message' => $this->body,
            'message_id' => $this->sid,
            'attributes' => $this->attributes,
            'to_channel_id' => $this->to,
            'dateCreated' => $this->dateCreated,

        ];
    }
}
