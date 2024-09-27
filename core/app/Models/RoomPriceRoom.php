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
        'status',  
    ];

    public $incrementing = false; 
    protected $primaryKey = ['room_id', 'price_id']; 
    protected $keyType = 'string';
}

