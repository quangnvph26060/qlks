<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegularRoomPrice extends Model
{
    use HasFactory;
    protected $table = 'regular_room_prices';

    protected $fillable = [
        'room_price_id',
        'hourly_price',
        'daily_price',
        'overnight_price',
    ];
}
