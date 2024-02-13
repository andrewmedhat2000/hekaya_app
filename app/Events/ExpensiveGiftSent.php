<?php

namespace App\Events;

use App\Models\Gift;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ExpensiveGiftSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $gift;
    public $sender;
    public $receiver;
    public $room_ids;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Gift $gift, User $sender, User $receiver, array $room_ids)
    {
        $this->gift = $gift;
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->room_ids = $room_ids;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return array_map(
            fn($id) => new Channel('room.'.$id),
            $this->room_ids
        );
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'ExpensiveGiftSent';
    }


    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
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
        ];
    }
}
