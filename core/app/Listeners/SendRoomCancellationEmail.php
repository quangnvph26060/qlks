<?php

namespace App\Listeners;

use App\Mail\RoomCancellationMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Events\RoomCancellationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRoomCancellationEmail implements ShouldQueue
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
        $user = $event->bookingRequest->user;
        $canceledRooms = $event->bookingRequest->bookingItems->whereIn('room_id', $event->cancelledRooms);
        try {
            Mail::to($user->email)->send(new RoomCancellationMail($canceledRooms, $user, $event->bookingRequest));
            Log::info('Email queued successfully for user: ' . $user->email);
        } catch (\Exception $e) {
            Log::error('Error sending room cancellation email: ' . $e->getMessage());
        }
    }
}
