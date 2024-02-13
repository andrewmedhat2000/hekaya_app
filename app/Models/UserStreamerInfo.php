<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserStreamerInfo extends Model
{
    //
    protected $table = 'users_streamers_info';

    protected $fillable = [
        'user_streamer_id', 'mins','gold'
    ];

    public function streamer()
    {
        return $this->belongsTo(UserStreamer::class, 'user_streamer_id');
    }

    public function scopeCurrentMonth(Builder $query)
    {
        $first_day_of_month = config('streamers.first_day_of_month', 1);
        $start_of_month = today()->day >= $first_day_of_month
            ? today()->setDay($first_day_of_month)
            : today()->addMonths(-1)->setDay($first_day_of_month);

        $query->whereDate('created_at', '>=', $start_of_month)->whereDate('created_at', '<', $start_of_month->addMonth());
    }
}
