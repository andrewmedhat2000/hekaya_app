<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

    public $upload_distination = '/uploads/images/games_slider/';

    protected $fillable = [
        'title','link', 'image'
    ];

    public const ROUTES = [
        'moon-game',
        'lucky-game',
        //'https://d1irpl18x20qhe.cloudfront.net/hekaya/fruits-guess/fruits-guess-full/index.html',
        //'https://d1irpl18x20qhe.cloudfront.net/hekaya/greedy-box/greedy-box-full/index.html',
    ];

    public function getRouteAttribute($value)
    {
        $is_constant = in_array($value, self::ROUTES);
        return $is_constant ? route($value) : $value;
    }

    public function setImageAttribute($value)
    {
        if (!$value instanceof UploadedFile) {
            $this->attributes['image'] = $value;
            return;
        }
        $image_name = str_random(60);
        $image_name = $image_name . '.' . $value->getClientOriginalExtension(); // add the extention
        $value->move(public_path($this->upload_distination), $image_name);
        $this->attributes['image'] = $image_name;
        return;
    }

    public function getImageAttribute($value)
    {
        if (!$value) {
            return asset('/panel-assets/images//01_img.png');
        }
        return strpos($value, 'http') !== false ? $value : asset($this->upload_distination . $value);
    }

}
