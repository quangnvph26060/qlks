<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPriceDayOfWeek extends Model
{
    use HasFactory;
    protected $table = 'room_prices_per_day_of_week';

    protected $fillable = [
        'room_price_id',
        'day_of_week',
        'hourly_price',
        'daily_price',
        'overnight_price',
    ];
}
