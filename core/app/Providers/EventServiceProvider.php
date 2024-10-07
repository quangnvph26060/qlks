<?php

namespace App\Providers;

use App\Events\RoomCancellationEvent;
use App\Events\EventRegisterUser;
use App\Listeners\ListenerRegisterUser;
use Illuminate\Support\ServiceProvider;
use App\Listeners\SendRoomCancellationEmail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $listen = [
        EventRegisterUser::class => [
            ListenerRegisterUser::class,
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
