<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomServiceProduct extends Model
{
    protected $table = 'room_service_products';

    protected $fillable = [
        'product_id',
        'service_id',
        'check_in_id',
        'room_code',
        'booking_date',
        'quantity',
        'total_payment',
        'creator',
        'unit_code',
        'price',
    ];
}
