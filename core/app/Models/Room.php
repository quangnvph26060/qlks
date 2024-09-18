<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Room extends Model {
    use GlobalStatus;

    protected $fillable = ['id','is_clean'];

    public function roomType() {
        return $this->belongsTo(RoomType::class);
    }

    public function booked() {
        return $this->hasMany(BookedRoom::class, 'room_id');
    }
    public function isRoomClean(){
        return $this->is_clean === 1;
    }
    public function getCleanStatusClass()
    {
        return $this->isRoomClean() ? 'badge-info' : 'badge-danger';
    }

    public function getCleanStatusSvg()
    {
        return $this->isRoomClean() ? 'is_clean' : 'no_clean';
    }
    public function getCleanStatusText()
    {
        return $this->isRoomClean() ? 'Đã dọn' : 'Chưa dọn';
    }
}
