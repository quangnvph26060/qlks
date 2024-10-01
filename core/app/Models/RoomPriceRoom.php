<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPriceRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'price_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'specific_date',
        'status'
    ];

    public $incrementing = false;
    protected $primaryKey = ['room_id', 'price_id'];
    protected $keyType = 'string';
}
