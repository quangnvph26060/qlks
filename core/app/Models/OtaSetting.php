<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtaSetting extends Model
{
    protected $table = 'ota_settings';

    protected $fillable = [
        'hotel_id',
        'allow_all_rooms',
        'allowed_room_types',
        'allowed_rooms',
        'status',
        'subdomain',
    ];

    protected $casts = [
        'allowed_room_types' => 'array',
        'allowed_rooms' => 'array',
    ];
    public function hotelFacility()
    {
        return $this->belongsTo(HotelFacility::class, 'hotel_id', 'id');
    }
}
