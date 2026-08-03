<?php

namespace App\Providers;

use App\Mail\Transport\SendagoTransport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Mail::extend('sendago', function (array $config) {
            return new SendagoTransport($config['member_id'], $config['secret']);
        });
    }
}
