<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use GlobalStatus,BelongsToTenant;
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_amenities');
    }
}
