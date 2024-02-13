<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserVerificationCodes extends Model
{
    protected $table = 'user_verification_codes';
    protected $fillable = [
        'user_id', 'code', 'expire_at'
    ];

    protected $dates = [
        'expire_at',
    ];

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
