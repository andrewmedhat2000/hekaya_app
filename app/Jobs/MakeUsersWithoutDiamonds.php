<?php

namespace App\Jobs;

use App\Models\BadgeUser;
use App\Models\FrameUser;
use App\Models\LoginSignUser;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class MakeUsersWithoutDiamonds implements ShouldQueue
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

        // Please note that this job will be used once
        $users = User::get();
        foreach($users as $user){
            $user->update([
                'diamonds' => 0,
                'gems' => 0,
                'charisma' => 0,
                'contribution' => 0,
                'diamonds_acquired' => 0
            ]);
        }

        return 'done';
    }
}
