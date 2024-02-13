<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginSignUser extends Model
{
    protected $table = 'login_signs_users';

    protected $fillable = ['user_id', 'loginsign_id', 'status', 'is_active'];

    public function loginSign()
    {
        return $this->belongsTo(LoginSign::class, 'login_sign_id');
    }

    public function scopeDaysToExpire($query)
    {
        $query->join('login_signs', 'login_signs.id', '=', 'login_signs_users.loginsign_id')
            ->select(['login_signs_users.*', 'login_signs.days_to_expire']);
    }
}
