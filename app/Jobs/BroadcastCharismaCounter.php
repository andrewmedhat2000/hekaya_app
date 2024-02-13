<?php

namespace App\Jobs;

use App\Events\CharismaCounter;
use App\Models\Room;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class BroadcastCharismaCounter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $room;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $ignore = Cache::get("ignore_charisma_jobs_before.{$this->room->id}");
        $last_job = Cache::get("last_charisma_job_time.{$this->room->id}");
        $now = microtime(true);

        if($ignore <= $now || $last_job + 2 < $now)
        {
            broadcast(new CharismaCounter($this->room));
            Cache::put("last_charisma_job_time.{$this->room->id}", $now);
        }

    }
}
