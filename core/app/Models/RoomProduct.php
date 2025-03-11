<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomProduct extends Model
{
    protected $table = 'room_products';

    protected $fillable = [
        'room_id',
        'product_id',
        'quantity',

    ];
}
