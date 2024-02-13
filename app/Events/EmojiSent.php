<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Emoji;
use App\Models\EmojiSend;
use App\Models\User;

class EmojiSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $emoji;
    public $user_id;
    public $room_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Emoji $emoji, $user_id, $room_id)
    {
        $this->emoji = $emoji;
        $this->user_id = $user_id;
        $this->room_id = $room_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('room.'.$this->room_id);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'EmojiSent';
    }


    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'emoji_id' => $this->emoji->id,
            'emoji_name' => $this->emoji->name,
            'emoji_image' => $this->emoji->image_url,
            'emoji_gif' => $this->emoji->gif_url,
            'user_id' => $this->user_id,
        ];
    }
}
