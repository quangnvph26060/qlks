<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToTenant;

class RoomFacility extends Model
{
    use BelongsToTenant;
    protected $table = 'room_facilities';

    protected $fillable = [
        'room_id',
        'unit_code',
        'subdomain',
        'facility_id',
        'created_at',
        'updated_at',

    ];
}
