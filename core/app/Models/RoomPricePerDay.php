<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPricePerDay extends Model
{
    use HasFactory;
    protected $table = 'room_prices_per_day';

    protected $fillable = [
        'room_price_id',
        'date',
        'hourly_price',
        'daily_price',
        'overnight_price',
    ];
}
