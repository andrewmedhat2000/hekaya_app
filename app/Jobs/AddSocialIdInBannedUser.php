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
use App\Models\BannedUser;


class AddSocialIdInBannedUser implements ShouldQueue
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
        $banned_users = BannedUser::get();
        foreach($banned_users as $banned){
            $banned->update(['social_id' => $banned->user->social_id]);
        }

        return 'done';
    }
}
