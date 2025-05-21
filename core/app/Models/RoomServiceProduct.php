<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class RoomServiceProduct extends Model
{
    use BelongsToTenant;
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
        'subdomain',
    ];
    public function product(){
        return $this->hasOne(Product::class, 'id', 'product_id');

    }
    public function  service(){
        return $this->hasOne(PremiumService::class, 'id','service_id');
    }
}
