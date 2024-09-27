<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use GlobalStatus;

    protected $fillable = ['id', 'is_clean'];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function booked()
    {
        return $this->hasMany(BookedRoom::class, 'room_id');
    }
    public function isRoomClean()
    {
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



    public function prices($data){

        foreach($data as $item){
            $roomPrice =  RoomPrice::find($item);
            if (!$roomPrice) {
                $notify[] = ['error', 'Giá không tồn tại'];
                return back()->withNotify($notify);
            }

            $roomPriceRoooms                    = new RoomPriceRoom();
            $roomPriceRoooms->room_id           = $this->id;
            $roomPriceRoooms->price_id          = $roomPrice->id;
            $roomPriceRoooms->start_date        = $roomPrice->start_date;
            $roomPriceRoooms->end_date          = $roomPrice->end_date;
            $roomPriceRoooms->start_time        = $roomPrice->start_time;
            $roomPriceRoooms->end_time          = $roomPrice->end_time;
            $roomPriceRoooms->specific_date     = $roomPrice->specific_date;
            $roomPriceRoooms->save();
        }
        //   return $this->belongsToMany(RoomPrice::class, 'room_price_rooms', 'room_id', 'price_id');
    }

    public function roomPrices()
    {
        return $this->belongsToMany(RoomPrice::class, 'room_price_rooms', 'room_id', 'price_id')

                    ->where('status', 'active');

    }
}
