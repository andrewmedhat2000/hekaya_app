<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this);
        $token = DB::table('oauth_access_tokens')->where('user_id','=','75192118')->get();
        //  $this['link']=$this['link']."uid=75192118&token=".$token[0]->id;
        //  $link=$this['link']."uid=75192118&token=".$token[0]->id
        return [
            'title'          => $this['title'] ?? '',
            'link'           => $this['title']."?uid=75192118&token=".$token[0]->id,//$this['link'] ?? '',
            'image'          => $this['logo_url'] ?? $this['image'] ?? '',
            'created_at'     => $this['created_at'] ?? '',
        ];
    }
}
