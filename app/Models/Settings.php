<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Settings extends Model
{
    protected $table = 'settings';

    protected $fillable = ['name', 'value'];

    public static $upload_path = 'uploads/settings/';

    public static function getValue($name)
    {
        return optional(self::firstWhere('name', $name))->value;
    }

    public static function setValue($name, $value)
    {
        return self::updateOrCreate(['name' => $name],['value' => $value]);
    }

    public function getValueAttribute($value)
    {
        if(!empty($value) && file_exists(public_path(static::$upload_path . $value))){
            return url(static::$upload_path . $value);
        }else{
            return $value;
        }
    }

    public function setValueAttribute($value)
    {
        if($value instanceof UploadedFile){
            $file_name = $this->attributes['name'] . '.' . $value->getClientOriginalExtension();
            $value->move(public_path(self::$upload_path), $file_name);
            $this->attributes['value'] = $file_name;
        }else{
            $this->attributes['value'] = $value;
        }
    }


}
