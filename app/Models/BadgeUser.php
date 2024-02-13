<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadgeUser extends Model
{
    protected $table = 'badges_users';

    protected $fillable = ['user_id', 'badge_id', 'status'];

    public function badge()
    {
        return $this->belongsTo(Badge::class);
    }

    public function scopeDaysToExpire($query)
    {
        $query->join('badges', 'badges.id', '=', 'badges_users.badge_id')
            ->select(['badges_users.*', 'badges.days_to_expire']);
    }
}
