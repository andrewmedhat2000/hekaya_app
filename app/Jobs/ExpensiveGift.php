<?php

namespace App\Jobs;

use App\Models\Gift;
use App\Models\Room;
use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Events\ExpensiveGiftSent;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ExpensiveGift implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $gift;
    public $sender;
    public $receiver;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Gift $gift, User $sender, User $receiver)
    {
        $this->gift = $gift;
        $this->sender = $sender;
        $this->receiver = $receiver;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $room_ids = array_keys(
            array_filter(Room::getRoomsWithUsersCount())
        );

        broadcast(new ExpensiveGiftSent($this->gift, $this->sender, $this->receiver, $room_ids));
    }
}
