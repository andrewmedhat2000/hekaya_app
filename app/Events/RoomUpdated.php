<?php

namespace App\Events;

use App\Http\Resources\RoomResource;
use App\Http\Resources\UserResource;
use App\Models\Room;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $room;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('room.'. $this->room->id);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'RoomUpdated';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        $this->room->load(['host:id,user_name,profile_pic', 'channel', 'rooms_drivers.driver', 'mutes']);

        return [
            'room' => new RoomResource($this->room),
            'changes' => $this->room->getChanges(),
        ];
    }
}
