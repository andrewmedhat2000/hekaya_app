<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserStreamer extends Model
{
    use SoftDeletes;

    protected $table = 'users_streamers';

    protected $fillable = [
        'streamer_id','streamer_admin_id','cost_per_hour', 'ends_at'
    ];

    public function streamer(){
        return $this->belongsTo(User::class,'streamer_id');
    }

    public function streamerAdmin(){
        return $this->belongsTo(User::class,'streamer_admin_id');
    }

    public function streamerInfo(){
        return $this->hasMany(UserStreamerInfo::class,'user_streamer_id');
    }

    public function streamerInfoThisMonth(){
        return $this->streamerInfo()->currentMonth();
    }

    protected function convertGemsToDiamonds()
    {
        $user = $this->streamer;

        $ratio = CurrencyWorth::firstOr(fn() => CurrencyWorth::make(['diamond' => 1, 'gem' => 1]));

        $diamond_factor = $ratio->diamond / $ratio->gem;
        $user->diamonds += $user->gems * $diamond_factor;
        $user->gems = 0;
        $user->save();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $first_day_of_month = config('streamers.first_day_of_month', 1);
            $model->ends_at = today()->day >= $first_day_of_month
                ? today()->addMonth()->setDay($first_day_of_month)
                : today()->setDay($first_day_of_month);

            $model->convertGemsToDiamonds();

            $active = self::where('streamer_id', $model->streamer_id)->where('streamer_admin_id', '!=', $model->streamer_admin_id)
                ->whereRaw("date(created_at) >= '{$model->ends_at->clone()->addMonths(-1)->toDateString()}'")->first();

            if($active) $active->delete();

            $exists = self::where('streamer_id', $model->streamer_id)->where('streamer_admin_id', $model->streamer_admin_id)
                ->whereRaw("date(created_at) >= '{$model->ends_at->clone()->addMonths(-1)->toDateString()}'")->first();
            if ($exists) {
                $exists->update(['ends_at' => $model->ends_at]);
                return false;
            }
        });
    }
}
