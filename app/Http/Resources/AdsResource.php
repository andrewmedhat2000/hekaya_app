<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdsResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'logo' => $this->logo,
            'logo_url' => $this->logo_url,
            'images' => $this->images,
        ];
    }
}
