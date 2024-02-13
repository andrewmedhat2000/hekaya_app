<?php

namespace App\Traits;

use App\Models\Target;
use App\Models\User;
use App\Models\UserStreamer;

trait Streamer
{
    public static $targets = null;
    protected $instance_target = 'unset';

    public function getTargetAttribute()
    {
        if($this->instance_target == 'unset')
            $this->instance_target = $this->getTarget();

        return $this->instance_target;
    }

    public function streamers()
    {
        return $this->belongsToMany(User::class, 'users_streamers', 'streamer_admin_id', 'streamer_id')->withTimestamps()->withPivot('streamer_id', 'streamer_admin_id', 'cost_per_hour');
    }

    public function streamersPivot()
    {
        return $this->hasMany(UserStreamer::class, 'streamer_admin_id');
    }

    public function streamerAdmins()
    {
        return $this->belongsToMany(User::class, 'users_streamers', 'streamer_id', 'streamer_admin_id')->withTimestamps()->withPivot('streamer_id', 'streamer_admin_id', 'cost_per_hour');
    }

    public function streamerAdminsPivot()
    {
        return $this->hasMany(UserStreamer::class, 'streamer_id');
    }

    public function currentStreamerAdminsPivot()
    {
        return $this->hasOne(UserStreamer::class, 'streamer_id')->where('ends_at', '>', today());
    }

    public function getMonthlyMinutes()
    {
        return optional($this->currentStreamerAdminsPivot)->streamerInfoThisMonth->sum('mins');
    }

    public function getMonthlyHours()
    {
        return $this->getMonthlyMinutes() / 60;
    }

    public function getHoursReward()
    {
        return optional($this->target)->hour_price * optional($this->target)->hoursFactor($this);
    }

    public function getMonthlyGolds()
    {
        return optional($this->currentStreamerAdminsPivot)->streamerInfoThisMonth->sum('gold');
    }

    public function getMonthlyDaysCount()
    {
        return optional($this->currentStreamerAdminsPivot)->streamerInfoThisMonth
        ->map(fn($i) => $i->date ?? $i->created_at->toDateString())->unique()->count();
    }

    public function getTarget(): ?Target
    {
        $golds = $this->getMonthlyGolds();

        if(!self::$targets) self::$targets = Target::orderBy('quantity', 'DESC')->get();

        return self::$targets->where('quantity', '<=', intval($golds))->first();
    }

    public function getNextTarget()
    {
        return self::$targets->reverse()->where('quantity', '>', $this->target->quantity ?? 0)->first();
    }

    public function getTargetProgress()
    {
        $previous_target = $this->target ?? new Target();
        $next_target = $this->getNextTarget();

        $current = $this->getMonthlyGolds();
        $previous = $previous_target->quantity ?? 0;
        $next = $next_target->quantity ?? $current;

        return compact('current', 'previous', 'next');
    }

    public function getMonthlyLevel()
    {
        return $this->target ? $this->target->title : 'No Level';
    }

    public function getMonthlyMoney()
    {
        $target = $this->target;

        $basic_salary = $this->getMonthlyBasicSalary();

        $reward = $target
            ? max($target->hour_price * $target->hoursFactor($this) - $target->daysDeduction($this), 0)
            : 0;

        return $basic_salary + $reward;
    }

    public function getMonthlyAdminCommission()
    {
        return $this->target->admin_price ?? 0;
    }

    public function getMonthlyBasicSalary()
    {
        $target = $this->target;
        $next_target = $this->getNextTarget();

        $current = $this->getMonthlyGolds();
        $previous = $target->quantity ?? 0;
        $next = $next_target->quantity ?? $current;
        $progress = $next_target
            ? round(($next_target->price - ($target->price ?? 0)) * ($current - $previous) / ($next - $previous), 2)
            : 0;

        return ($target->price ?? 0) + $progress;
    }

    public function daysTargetCompleted()
    {
        $target = $this->target ?? $this->getNextTarget();

        return $this->getMonthlyDaysCount() >= ($target->days ?? 0);
    }

    public function hoursTargetCompleted()
    {
        $target = $this->target ?? $this->getNextTarget();

        return $this->getMonthlyMinutes() >= ($target->mins ?? 0);
    }

}
