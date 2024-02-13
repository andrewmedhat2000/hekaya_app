<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrameUser extends Model
{
    protected $table = 'frames_users';

    protected $fillable = ['frame_id', 'user_id', 'status', 'bought_at', 'is_active'];

    public function frame()
    {
        return $this->belongsTo(Frame::class);
    }

    public function scopeDaysToExpire($query)
    {
        $query->join('frames', 'frames.id', '=', 'frames_users.frame_id')
            ->select(['frames_users.*', 'frames.days_to_expire']);
    }
}
