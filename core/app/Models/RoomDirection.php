<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
class RoomDirection extends Model
{
    use HasFactory,BelongsToTenant;

    protected $table = 'room_directions';

    protected $fillable = [
        'code',
        'name',
        'price_offset',
        'unit_code',
        'subdomain',
    ];

    /**
     * Một hướng phòng có thể áp dụng cho nhiều phòng.
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'direction_id');
    }
}
