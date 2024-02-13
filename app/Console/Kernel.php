<?php

namespace App\Console;

use App\Jobs\DisableExpiredStoreProducts;
use App\Jobs\ResetStreamersGems;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\TopUsersCommand',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('get:topUsers')->everyFifteenMinutes();
        // $schedule->call(function(){
        //     DisableExpiredStoreProducts::dispatchNow();
        // })->daily();

        $schedule->call(function(){
            DisableExpiredStoreProducts::dispatchNow();
        })->hourly();

        $schedule->job(new ResetStreamersGems())->monthlyOn(config('streamers.first_day_of_month'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
