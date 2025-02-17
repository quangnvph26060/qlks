<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomStatus extends Model
{
    use HasFactory;
    protected $table = 'room_status';

    protected $fillable = [
        'status_code',
        'status_name',
        'unit_code',
    ];
}
