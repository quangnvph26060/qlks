<?php

namespace App\Providers;

use App\Events\RoomCancellationEvent;
use Illuminate\Support\ServiceProvider;
use App\Listeners\SendRoomCancellationEmail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $listen = [
        'App\Events\EventRegisterUser' => [
            'App\Listeners\ListenerRegisterUser',
        ],
        RoomCancellationEvent::class => [
            SendRoomCancellationEmail::class,
        ],
    ];


    public function register(): void {}

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
