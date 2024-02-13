<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppMobileVersion extends Model
{
    //

    protected $table = 'app_version';

    protected $fillable = [
        'mobile_os','update_required','version'
    ];
}
