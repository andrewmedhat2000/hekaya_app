<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Files extends Model
{
    //
    protected $table = 'files';

    protected $fillable = [
        'file','description','user_id',
    ];


    protected $appends = [
        'file_url',
    ];

    public $upload_distination = '/uploads/files/';


    public function setFileAttribute($value)
    {
        if (!$value instanceof UploadedFile) {
            $this->attributes['file'] = $value;
            return;
        }
        $image_name = str_random(60);
        $image_name = $image_name . '.' . $value->getClientOriginalExtension(); // add the extention
        $value->move(public_path($this->upload_distination), $image_name);
        $this->attributes['file'] = $image_name;
    }
    public function getFileUrlAttribute()
    {
        if (!$this->file) {
            return null;
        }
        return strpos($this->file, 'http') !== false ? $this->file : asset($this->upload_distination . $this->file);
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

}
