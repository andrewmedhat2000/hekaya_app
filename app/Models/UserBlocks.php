<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBlocks extends Model
{
    //
    protected $table = 'user_blocks';

    protected $fillable = [
        'user_id','blocked_id', 'status'
    ];

}
