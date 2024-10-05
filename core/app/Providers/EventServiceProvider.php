<?php

namespace App\Providers;

use App\Events\EventRegisterUser;
use App\Listeners\ListenerRegisterUser;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $listen = [
        EventRegisterUser::class => [
            ListenerRegisterUser::class,
        ],
    ];
    
    
    public function register(): void
    {
       
    }
    
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
