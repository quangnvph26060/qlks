<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomStatusHistory extends Model
{
    //
    protected $table = 'room_status_history';

    protected $fillable = [
        'room_id',
        'status_code',
        'start_date',
        'end_date',
        'unit_code',
    ];
    public function roomStatus(){
        return $this->hasOne(RoomStatus::class, 'id', 'status_code');
    }

    public function room(){
        return $this->hasOne(Room::class, 'id', 'room_id');
    }
}
