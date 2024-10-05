<?php

namespace App\Listeners;

use App\Events\EventRegisterUser;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ListenerRegisterUser
{
    /**
     * Create the event listener.
     */
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     */
    public function handle(EventRegisterUser $event): void
    {
      
    }
}
