<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceType extends Model
{
    protected $table  = 'price_types';
    protected $fillable = [
        'name'
    ];
    public function roomPrices()
    {
        return $this->hasMany(RoomPrice::class, 'price_type_id');
    }
    public function holidayPrices()
    {
        return $this->hasMany(HolidayPrice::class, 'price_type_id');
    }
}
