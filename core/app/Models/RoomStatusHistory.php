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
    public function bookingData()
    {
        return $this->hasMany(RoomBooking::class, 'checkin_date', 'start_date')
            ->join('room_status_history', function ($join) {
                $join->on('room_status_history.end_date', '=', 'room_booking.checkout_date')
                     ->whereColumn('room_booking.room_code', 'room_status_history.room_id');
            })
            ->select('room_booking.*');
    }
    
    
    public function checkInData()
    {
        return $this->hasMany(CheckIn::class, 'checkin_date', 'start_date')
            ->join('room_status_history', function ($join) {
                $join->on('room_status_history.end_date', '=', 'check_in.checkout_date')
                     ->whereColumn('check_in.room_code', 'room_status_history.room_id');
            })
            ->select('check_in.*');
    }
    
}
