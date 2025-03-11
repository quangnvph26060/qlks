<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomFacility extends Model
{
    protected $table = 'room_facilities';

    protected $fillable = [
        'room_id',
        'facility_id',
        'created_at',
        'updated_at',

    ];
}
