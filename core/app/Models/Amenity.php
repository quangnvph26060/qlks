<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use GlobalStatus;
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_amenities');
    }
}
