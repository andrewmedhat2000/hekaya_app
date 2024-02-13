<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserStreamer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ResetStreamersGems implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $streamers = User::whereHas('streamerAdminsPivot', function($q) {
            $q->where('ends_at', today()->toDateString());
        });

        $streamers->each(function($user) {
            $user->update(['gems' => 0]);
        });

        UnassignInactiveStreamers::dispatch()->delay(now()->addDays(3));
    }
}
