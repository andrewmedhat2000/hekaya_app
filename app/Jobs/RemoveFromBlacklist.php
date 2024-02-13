<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;


class RemoveFromBlacklist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $room_id;
    public $user_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($room_id, $user_id)
    {
        $this->room_id = $room_id;
        $this->suser_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::table('room_blacklist')->where('room_id', $this->room_id)->where('user_id', $this->user_id)->delete();
    }
}
