<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayOfWeek extends Model
{
    protected $table  = 'days_of_week';
    protected $fillable = [
        'name'
    ];
    public function roomPrices()
    {
        return $this->hasMany(RoomPrice::class, 'day_of_week_id');
    }
}
