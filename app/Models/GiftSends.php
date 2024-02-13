<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GiftSends extends Model
{
    protected $table = 'gift_users';

    protected $fillable = ['gift_id', 'from_user_id', 'to_user_id', 'status', 'room_id', 'price'];

    public function gift()
    {
        return $this->belongsTo(Gift::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function scopeDaily($query)
    {
        $query->where('created_at', '>', now()->setTime(0,0));
    }

    public function scopeWeekly($query)
    {
        $query->where('created_at', '>', now()->setTime(0,0)->subDays(now()->dayOfWeek));
    }

    public function scopeMonthly($query)
    {
        $query->where('created_at', '>', now()->setTime(0,0)->setDay(1));
    }

    public function scopeSender($query)
    {
        $query->selectRaw('`from_user_id`, sum(`price`) as `price`')
            ->groupBy('from_user_id')
            ->orderBy('price', 'desc')
            ->with('sender');
    }

    public function scopeReceiver($query)
    {
        $query->selectRaw('`to_user_id`, sum(`price`) as `price`')
            ->groupBy('to_user_id')
            ->orderBy('price', 'desc')
            ->with('receiver');
    }

    public function scopeRoom($query)
    {
        $query->selectRaw('`room_id`, sum(`price`) as `price`')
            ->groupBy('room_id')
            ->orderBy('price', 'desc')
            ->with('room');
    }

}
