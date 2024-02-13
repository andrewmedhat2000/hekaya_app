<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GuessGameResource extends JsonResource
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
            'game_id' => $this->id,
            'challenger_id' => $this->challenger->id,
            'challenger_username' => $this->challenger->name,
            'challenger_image' => $this->challenger->profile_pic_url,
            'challenger_shape' => $this->challenger_shape->gif_url,

            'diamonds' => $this->package->value,

            // 'challenged_id' => $this->challenged->id ,
            // 'challenged_username' => $this->challenged->user_name,
            // 'challenged_image' => $this->challenged->profile_pic_url,
            // 'challenged_shape' => $this->challenged_shape->gif_url,

            'challenged_id' => $this->whenLoaded('challenged', fn()=> $this->challenged->id ),
            'challenged_username' => $this->whenLoaded('challenged', fn()=> $this->challenged->name ),
            'challenged_image' => $this->whenLoaded('challenged', fn()=> $this->challenged->profile_pic_url ),
            'challenged_shape' => $this->whenLoaded('challenged_shape', fn()=> $this->challenged_shape->gif_url ),

            'winner_id' => $this->winner_id,
        ];
    }
}
