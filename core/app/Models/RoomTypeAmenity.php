<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class RoomTypeAmenity extends Model
{
    use BelongsToTenant;
    protected $table = 'room_amenities';

    protected $fillable = [
        'room_id',
        'amenities_id',
        'created_at',
        'updated_at',

    ];
}
