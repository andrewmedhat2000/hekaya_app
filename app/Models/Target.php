<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Target extends Model
{
    //
    protected $table = 'targets';

    protected $fillable = [
        'title', 'price', 'quantity', 'description', 'currency_id','mins','hour_price','admin_price', 'days'
    ];


    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function hoursFactor(User $user)
    {
        $mins = $user->getMonthlyMinutes();

        if ($mins >= $this->mins) return 1;

        $target_hours_factor = config('streamers.target_hours_factor', []);
        krsort($target_hours_factor);

        return Arr::first(
            $target_hours_factor,
            fn($value, $key) => $mins > $key,
            0
        );
    }

    public function daysDeduction(User $user)
    {
        return $this->days
            ? max($this->days - $user->getMonthlyDaysCount(), 0) * config('streamers.deduction_per_day')
            : 0;
    }

    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'package_users', 'package_id', 'user_id')->withTimestamps()->withPivot('status');
    // }
}
