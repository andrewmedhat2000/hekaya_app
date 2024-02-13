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

class DisableExpiredStoreProducts implements ShouldQueue
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
        $frames = FrameUser::where('status', 0)->daysToExpire()->whereNotNull('days_to_expire')->get();
        $badges = BadgeUser::where('status', 0)->daysToExpire()->whereNotNull('days_to_expire')->get();
        $login_signs = LoginSignUser::where('status', 0)->daysToExpire()->whereNotNull('days_to_expire')->get();

        // $today = today();
        $today = now();
        foreach($frames->concat($badges)->concat($login_signs) as $item){
            $expires_at = Carbon::parse($item->created_at)->addDays($item->days_to_expire);
            // info([
            //     'item_id' => $item->id,
            //     'days_to_expire' => $item->days_to_expire,
            //     'today' => $today->format('Y-m-d H:i a'),
            //     'expires_at' => $expires_at->format('Y-m-d H:i a'),
            //     'greaterThan' => $today->greaterThan($expires_at),
            // ]);
            if ($today->greaterThan($expires_at)){
                $item->update(['status' => 1, 'is_active' => 0]);
            }
        }

        $frames_expired = FrameUser::where('status', 1)->where('is_active', 1)->get();
        $login_signs_expired = LoginSignUser::where('status', 1)->where('is_active', 1)->get();
        foreach($frames_expired->concat($login_signs_expired) as $item){
            $item->update(['is_active' => 0]);
        }

        // info('------------------------------------------------------------------------------------');

    }
}
