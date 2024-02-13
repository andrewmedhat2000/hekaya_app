<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Notify\V1\Service\BindingInstance;


class CreateNotificationBinding implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($identity, $address)
    {
        $this->identity = $identity;
        $this->address = $address;
        $this->sid = config('services.twilio.sid');
        $this->token = config('services.twilio.token');
        $this->notify = config('services.twilio.notify');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client($this->sid, $this->token);

        $binding = $client->notify->v1->services($this->notify)
                            ->bindings
                            ->create($this->identity, "fcm", $this->address);

        if($binding instanceof BindingInstance){
            Log::info('twilio binding:',$binding->toArray());
        }else{
            Log::info('twilio binding:',$binding);
        }
    }
}
