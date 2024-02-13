<?php

namespace App\Events;

use App\Http\Resources\RoomResource;
use App\Http\Resources\UserResource;
use App\Models\Gift;
use App\Models\Room;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GiftSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;
    public $gift;
    public $sender;
    public $receiver;
    public $level;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Room $room, Gift $gift, User $sender, User $receiver,string $level)
    {
        $this->room = $room;
        $this->gift = $gift;
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->level = $level;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('room.'.$this->room->id);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'GiftSent';
    }


    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $this->room->load(['host:id,user_name,name,profile_pic', 'channel', 'rooms_drivers.driver', 'mutes']);

        return [
            'gift_id' => $this->gift->id,
            'gift_image' => $this->gift->image_url,
            'gift_gif' => $this->gift->gif_url,
            'gift_price' => $this->gift->price,
            'type' => $this->gift->type,
            'sender_name' => $this->sender->name,
            'sender_image' => $this->sender->profile_pic_url,
            'receiver_name' => $this->receiver->name,
            'receiver_id' => $this->receiver->id,
            'receiver_image' => $this->receiver->profile_pic_url,
            'level'=>$this->level
           
        ];
    }
}
