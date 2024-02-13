<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class LoginSign extends Model
{
    //
    protected $table = 'login_signs';

    protected $fillable = [
        'name','price', 'image', 'days_to_expire'
    ];


    protected $appends = [
        'image_url',
    ];

    public $upload_distination = '/uploads/images/gifts/';


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

    public function users()
    {
        return $this->belongsToMany(User::class, 'login_signs_users','loginsign_id', 'user_id')->withTimestamps()->withPivot('status', 'is_active');
    }
}
