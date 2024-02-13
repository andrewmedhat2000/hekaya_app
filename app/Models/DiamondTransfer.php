<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiamondTransfer extends Model
{
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'diamonds',
    ];

    public function distributor()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
