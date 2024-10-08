<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomImage extends Model
{
    use GlobalStatus;
    use HasFactory;

    protected $casts = [
        'keywords' => 'array',
        'beds' => 'array'
    ];

    protected $fillable = [
        'room_id',
        'image'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
