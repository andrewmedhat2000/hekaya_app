<?php

namespace App\Events;

use App\Models\Room;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MoonGiftEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $diamonds;
    public $room_ids;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($diamonds, User $user)
    {
        $this->user = $user;
        $this->diamonds = $diamonds;
        $this->room_ids = array_keys(
            array_filter(Room::getRoomsWithUsersCount())
        );
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
        return 'MoonGiftEvent';
    }


    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'diamonds'      => $this->diamonds,
            'user_name'     => $this->user->name,
            'user_image'    => $this->user->profile_pic_url,
        ];
    }
}
