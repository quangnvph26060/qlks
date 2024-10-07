<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingRequestItem extends Model
{
    use HasFactory;

    protected $table = 'booking_request_items';

    public $timestamps = false;

    protected $fillable = [
        'booking_request_id',
        'room_id',
        'unit_fare',
        'tax_charge',
        'status',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class);
    }
}
