<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuckyGiftScore extends Model
{
    protected $fillable = ['user_id', 'points', 'round_value', 'rounds_number'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
