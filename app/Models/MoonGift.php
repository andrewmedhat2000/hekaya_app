<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoonGift extends Model
{
    protected $fillable = ['round_number', 'round_value', 'minimum_win', 'maximum_win'];
}
