<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Ads extends Model
{
    //
    protected $table = 'ads';

    protected $fillable = [
        'title','description','logo',
    ];

    
    protected $appends = [
        'logo_url',
    ];

    public $upload_distination = '/uploads/images/app/';

    public function setLogoAttribute($value)
    {
        if (!$value instanceof UploadedFile) {
            $this->attributes['logo'] = $value;
            return;
        }
        $image_name = str_random(60);
        $image_name = $image_name . '.' . $value->getClientOriginalExtension(); // add the extention
        $value->move(public_path($this->upload_distination), $image_name);
        $this->attributes['logo'] = $image_name;
    }
    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return asset('/panel-assets/images/profile-picutre/01_img.png');
        }
        return strpos($this->logo, 'http') !== false ? $this->logo : asset($this->upload_distination . $this->logo);
    }

   
    /**
     * Relations
     */
    public function images()
    {
        return $this->belongsToMany(Image::class, 'ads_images','ad_id','image_id')->withTimestamps();
    }
}
