<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoonGiftScore extends Model
{
    protected $fillable = ['user_id', 'diamond_win', 'diamond_lost', 'diamond_lost', 'rounds_number'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
