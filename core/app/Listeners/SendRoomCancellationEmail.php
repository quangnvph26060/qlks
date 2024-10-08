<?php

namespace App\Listeners;

use App\Mail\RoomCancellationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Events\RoomCancellationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRoomCancellationEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RoomCancellationEvent $event): void
    {
        $user = $event->data['bookingRequest']->user;
        $canceledRooms = $event->data['bookingRequest']->bookingItems->whereIn('room_id', $event->data['cancelledRooms']);
        $data =  findTemplateEmail($event->data['type']);
        $subject    = $data->subject;
        // Mail::send('templates.mail.email-register', ['fullname'=>$fullname, 'username'=>$username, 'password'=>$password], 
        // function($message) use ($username,$subject) {
        //     $message->to($username)->subject($subject);
        // });
        try {
            Mail::to($user->email)->send(new RoomCancellationMail($canceledRooms, $user, $event->data['bookingRequest']));
            Log::info('Email queued successfully for user: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Error sending room cancellation email: ' . $e->getMessage());
        }
    }
}
