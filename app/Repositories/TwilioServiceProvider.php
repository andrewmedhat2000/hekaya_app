<?php
namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class TwilioServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            TwilioRepositoryInterface::class,
            TwilioRepository::class
        );
    }

}