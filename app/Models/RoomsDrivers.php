<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomsDrivers extends Model
{


    protected $table = 'rooms_drivers';

    protected $fillable = [
        'driver_sid', 'driver_id', 'room_id', 'sit_num', 'status', 'channel_sid', 'muted', 'muted_by_admin'
    ];



    public function driver(){

        return $this->belongsTo(User::class, 'driver_id');
    }

    public function room(){

        return $this->belongsTo(Room::class, 'room_id');
    }

    public static function boot()
    {
        parent::boot();

        static::created(function($model) {
            if ($model->status == 'connected' && $model->driver->hasRole('streamer')) {
                $user_streamer = $model->driver->currentStreamerAdminsPivot;

                if($user_streamer)
                    $user_streamer->streamerInfo()->create(['mins' => 0]);
            }
        });

        static::deleting(function($model) {
            if ($model->status == 'connected' && $model->driver->hasRole('streamer')) {
                $user_streamer = $model->driver->currentStreamerAdminsPivot;

                if($user_streamer) {
                    $info = $user_streamer->streamerInfo()->where('mins', 0)->latest()->first();
                    $info ? $info->update([
                        'mins' => now()->diffInMinutes($model->created_at)
                    ]) : null;
                }
            }
        });
    }


}
