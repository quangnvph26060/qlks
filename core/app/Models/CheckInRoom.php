<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckInRoom extends Model
{
    use HasFactory;

    protected $table = 'check_in_rooms';

    protected $fillable = [
        'booking_id',
        'room_type_id',
        'room_id',
        'booked_for',
        'fare',
        'check_in',
        'check_out',
        'check_in_at',
        'cancellation_fee',
        'status',
        'key_status',
        'option_room',
        'tax_charge',
        'unit_code',
        'book_room_id'
    ];
    public function booking() {
        return $this->belongsTo(Booking::class, 'booking_id','id');
    }
    public function userBooking()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}
