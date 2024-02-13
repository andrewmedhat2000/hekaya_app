<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Package extends Model
{
    //
    protected $table = 'packages';

    protected $fillable = [
        'title','price','quantity', 'description', 'image','currency_id'
    ];


    protected $appends = [
        'image_url',
    ];

    public $upload_distination = '/uploads/images/packages/';


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

    public function currency(){
        return $this->belongsTo(Currency::class,'currency_id');
    }

    public function users(){
        return $this->belongsToMany(User::class,'package_users','package_id','user_id')->withTimestamps()->withPivot('status');
    }
}
