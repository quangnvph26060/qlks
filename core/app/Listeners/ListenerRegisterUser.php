<?php

namespace App\Listeners;

use App\Events\EventRegisterUser;
use App\Models\EmailTemplate;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
    public function handle(EventRegisterUser $event)
    {

        $data =  findTemplateEmail($event->data['type']);
        $fullname   = $event->data['name']; 
        $username   = $event->data['email']; 
        $password   = $event->data['password']; 
        $subject    = $data->subject;
        Mail::send('templates.mail.email-register', ['fullname'=>$fullname, 'username'=>$username, 'password'=>$password], 
        function($message) use ($username,$subject) {
            $message->to($username)->subject($subject);
        });
    }
}
