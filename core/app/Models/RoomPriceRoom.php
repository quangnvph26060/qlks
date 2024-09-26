<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPriceRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_price_id',
        'room_id',
    ];
}
