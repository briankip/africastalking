<?php

namespace NotificationChannels\AfricasTalking;

use Illuminate\Support\ServiceProvider;

class AfricasTalkingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Bootstrap code here.
        $this->app->when(AfricasTalkingChannel::class)
            ->needs(AfricasTalking::class)
            ->give(function () {
                $pusherConfig = config('broadcasting.connections.pusher');

                return new Pusher(
                    $pusherConfig['key'],
                    $pusherConfig['secret'],
                    $pusherConfig['app_id']
                );
            });

    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
