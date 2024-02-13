<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Gift;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Api\TwilioController;
use Twilio\Rest\Notify\V1\Service\NotificationInstance;

class TwilioNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sid, Gift $gift, User $user)
    {
        $this->gift = $gift;
        $this->sid = $sid;
        $this->user = $user;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $notification = TwilioController::GiftNotification(
            $this->sid, $this->gift->id, $this->user
        );

        if($notification instanceof NotificationInstance){
            Log::info('twilio response:',$notification->toArray());
        }else{
            Log::info('twilio response:',[$notification]);
        }
    }
}
