<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTypePrice extends Model
{
    use HasFactory;
    protected $table = 'room_type_price';

    protected $fillable = [
        'room_type_id',
        'setup_pricing_id',
        'unit_price',
        'overtime_price',
        'extra_person_price',
        'auto_calculate',
        'unit_code',
        'price_validity_period',
    ];
    public function setupPricing(){
        return $this->belongsTo(SetupPricing::class, 'setup_pricing_id', 'id');
    }
    public function roomType(){
        return $this->belongsTo(RoomType::class, 'room_type_id', 'id');
    }
}
