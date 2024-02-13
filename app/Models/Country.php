<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Country extends Model
{
    //
    protected $table = 'countries';

    protected $fillable = [
        'name', 'code','phone_code', 'image'
    ];

    public $upload_distination = '/uploads/images/countries/';

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
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('/panel-assets/images/profile-picutre/01_img.png');
        }
        return strpos($this->image, 'http') !== false ? $this->image : asset($this->upload_distination . $this->image);
    }

    public function cities(){
        return $this->hasMany(City::class,'country_id');
    }
}
