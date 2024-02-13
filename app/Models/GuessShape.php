<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class GuessShape extends Model
{
    //

    protected $table = 'guess_shapes';

    protected $fillable = [
        'name','image', 'gif',
    ];

    protected $appends = [
        'image_url', 'gif_url',
    ];

    public $upload_distination = '/uploads/images/shapes/';


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





    public function challenger_guesses(){
        return $this->hasMany(GuessGame::class, 'challenger_shape_id');
    }

    public function challenged_guesses(){
        return $this->hasMany(GuessGame::class, 'challenged_shape_id');
    }




}
