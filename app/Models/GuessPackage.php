<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class GuessPackage extends Model
{
    //

    protected $table = 'guess_packages';

    protected $fillable = [
        'name','value',
    ];


    public function guess_games(){
        return $this->hasMany(GuessGame::class, 'package_id');
    }


}
