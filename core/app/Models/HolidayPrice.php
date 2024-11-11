<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HolidayPrice extends Model
{
    protected $table  = 'holiday_prices';
    protected $fillable = [
        'room_id',
        'price_type_id',
        'holiday_date',
        'first_hour',
        'additional_hour',
        'full_day',
        'overnight',
    ];
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
    public function priceType()
    {
        return $this->belongsTo(PriceType::class, 'price_type_id');
    }
}
