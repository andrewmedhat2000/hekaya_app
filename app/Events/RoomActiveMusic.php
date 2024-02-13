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
use App\Models\Room;
use App\Models\RoomMusic;
use App\Http\Resources\RoomMusicResource;

class RoomActiveMusic implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $music_object;
    public $delete;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RoomMusic $music_object, $delete = false)
    {
        $this->music_object = $music_object;
        $this->delete = $delete;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('room.'.$this->music_object->room_id);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'RoomMusic';
    }


    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        if($this->delete){
            $this->music_object->delete();
        }
        return [
            'data' => new RoomMusicResource($this->music_object),
        ];
        // return new RoomMusicResource($this->music_object);
    }
}
