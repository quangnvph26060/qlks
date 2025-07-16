<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
class RoomProduct extends Model
{
use BelongsToTenant;
    protected $table = 'room_products';

    protected $fillable = [
        'room_id',
        'product_id',
        'warehouse_id',
        'quantity',
        'unit_code',
        'subdomain'
    ];
}
