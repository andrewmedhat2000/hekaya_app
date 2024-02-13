<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Gift extends Model
{
    //

    protected $table = 'gifts';

    protected $fillable = [
        'name','price', 'description', 'image', 'gif', 'type', 'display_time', 'game_gift'
    ];


    protected $appends = [
        'image_url', 'gif_url',
    ];

    public const GAME_GIFTS = [
        "lucky_gift" => 1
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

    public function setGifAttribute($value)
    {
        if (!$value instanceof UploadedFile) {
            $this->attributes['gif'] = $value;
            return;
        }
        $gif_name = str_random(60);
        $gif_name = $gif_name . '.' . $value->getClientOriginalExtension(); // add the extention
        $value->move(public_path($this->upload_distination), $gif_name);
        $this->attributes['gif'] = $gif_name;
    }

    public function getGifUrlAttribute()
    {
        if (!$this->gif) {
            return null;
        }
        return strpos($this->gif, 'http') !== false ? $this->gif : asset($this->upload_distination . $this->gif);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'lucky_gift_user', 'gift_id', 'user_id');
    }

    public function bayer(){
        return $this->belongsToMany(User::class,'gifts_users_history','gift_id','user_id')->withTimestamps()->withPivot('status');
    }

    public function getUsersReceived(){
        return $this->belongsToMany(User::class,'gift_users','gift_id','to_user_id')->withPivot('to_user_id','status')->withTimestamps();
    }
    public function getUsersSend(){
        return $this->belongsToMany(User::class,'gift_users','gift_id','from_user_id')->withPivot('from_user_id','status')->withTimestamps();
    }

    public function luckyGifts()
    {
        return $this->belongsToMany(User::class, 'user_lucky_gift');
    }
}
