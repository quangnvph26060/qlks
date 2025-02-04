<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use GlobalStatus, SoftDeletes;

    protected $table = 'rooms';
    // protected $fillable = [ 'is_clean','unit_code'];

    // insert hay get ra đều trả ra đúng mảng 
    protected $casts = [
        'keywords' => 'array',
        'beds'     => 'array'
    ];
    protected $primaryKey = 'id';
    // price
    // Trong model Room
    // public function regularRoom()
    // {
    //     return RegularRoomPrice::where('room_price_id', $this->id)->first();
    // }
    // public function roomPricePerDay()
    // {
    //     return RoomPricePerDay::where('room_price_id', $this->id)->first();
    // }
    // public function regularRoom()
    // {
    //     return $this->hasOne(RegularRoomPrice::class, 'id', 'id');
    // }
    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'room_amenities', 'room_id', 'amenities_id')->withTimestamps();
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'room_products', 'room_id', 'product_id')->withPivot('quantity');
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'room_facilities', 'room_id', 'facility_id')->withTimestamps();
    }
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }
    public function checkroom()
    {
        // Kiểm tra nếu có bản ghi trong checkins
        if ($this->checkins()->exists()) {
            return $this->checkins;
        }
    
        // Nếu không, trả về booked
        return $this->booked;
    }
    
    

    public function booked()
    {
        return $this->hasMany(BookedRoom::class, 'room_id', 'id');
    }
    public function checkins()
    {
        return $this->hasMany(CheckInRoom::class, 'room_id', 'id');
    }
    // public function roomPrice()
    // {
    //     return $this->hasMany(RegularRoomPrice::class, 'room_price_id', 'id');
    // }
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


    public function updatePrices($data)
    {
        foreach ($data as $item) {


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
    }
    public function prices()
    {
        return $this->belongsToMany(RoomPrice::class, 'room_price_rooms', 'room_id', 'price_id')->withPivot('start_date', 'end_date', 'start_time', 'end_time', 'specific_date', 'status');
    }
    public function scopeActive($query)
    {
        return $query->where([
            'rooms.status' => Status::ROOM_ACTIVE
            // 'rooms.is_clean'=> Status::ROOM_CLEAN_ACTIVE
        ]);
    }

    public function roomPrices()
    {
        return $this->belongsToMany(RoomPrice::class, 'room_price_rooms', 'room_id', 'price_id')
            ->where('room_prices.status', 'active')
            ->withPivot('room_id', 'price_id', 'start_date', 'end_date', 'start_time', 'end_time', 'specific_date', 'status');
    }
    public function roomPricesActive()
    {
        return $this->belongsToMany(RoomPrice::class, 'room_price_rooms', 'room_id', 'price_id')
            ->where('room_prices.status', 'active')
            ->withPivot('room_id', 'price_id', 'start_date', 'end_date', 'start_time', 'end_time', 'specific_date', 'status')
            ->wherePivot('room_price_rooms.status', 1);
    }

    public function wishList()
    {
        return $this->hasOne(Wishlist::class);
    }

    public function featureBadge(): Attribute
    {
        return new Attribute(
            function () {
                $html = '';

                if ($this->is_featured == Status::ROOM_FEATURED) {
                    $html = '<span class="badge badge--primary">' . trans('Nổi bật') . '</span>';
                    //Featured
                } else {
                    $html = '<span><span class="badge badge--dark">' . trans('Không có gì nổi bật') . '</span></span>';
                }   //Unfeatured

                return $html;
            }
        );
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', Status::ROOM_TYPE_FEATURED);
    }

    public function roomPriceNow(){
        $day = Carbon::now()->toDateString();
        $thu = Carbon::now()->locale('en')->isoFormat('dddd');

        if($this->roomPriceDayNow($day)){
            return  $this->roomPriceDayNow($day);
        }elseif($this->roomPriceDayOfWeekNow($thu)){
            return  $this->roomPriceDayOfWeekNow($thu);
        }

        return  $this->regularRoom();
        //return $this->code;
    }

    public function roomPriceDayNow($date)
    {
        return RoomPricePerDay::where('room_price_id', $this->id)->where('date', $date)->first();
    }

    public function roomPriceDayOfWeekNow($dayofweek)
    {
        return RoomPriceDayOfWeek::where('room_price_id', $this->id)->where('day_of_week', $dayofweek)->first();
    }

    public function bookedRooms(){
         return BookedRoom::where('room_id', $this->id)->get();
    }
    public function roomBooking()
    {
        return $this->hasMany(RoomBooking::class, 'room_code');
    }
    public function roomCheckIn()
    {
        return $this->hasMany(CheckIn::class, 'room_code');
    }
}
