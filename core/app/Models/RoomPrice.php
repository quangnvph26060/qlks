<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPrice extends Model
{
    use HasFactory;
    protected $table  = 'rooms_prices';

    // const STATUS_ACTIVE = 'active';
    // const STATUS_INACTIVE = 'inactive';

    // protected $fillable = [
    //     'code',
    //     'name',
    //     'price',
    //     'start_date',
    //     'end_date',
    //     'status',
    //     'start_time',
    //     'end_time',
    //     'specific_date'
    // ];

    // public function scopeActive($query)
    // {
    //     return $query->where('status', self::STATUS_ACTIVE);
    // }
    protected $fillable = [
        'room_id',
        'price_type_id',
        'day_of_week_id',
        'first_hour',
        'additional_hour',
        'full_day',
        'overnight',
    ];

    // public function rooms()
    // {
    //     return $this->belongsToMany(Room::class, 'room_price_rooms', 'price_id', 'room_id')->withPivot('start_date', 'end_date', 'start_time', 'end_time', 'specific_date', 'status');
    // }

    public function rooms()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
    public function priceType()
    {
        return $this->belongsTo(PriceType::class, 'price_type_id');
    }
    public function dayOfWeek()
    {
        return $this->belongsTo(DayOfWeek::class, 'day_of_week_id');
    }
}
