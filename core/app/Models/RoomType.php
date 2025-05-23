<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\BelongsToTenant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Log;

class RoomType extends Model
{
    use GlobalStatus, BelongsToTenant;


    protected $table = 'room_types';


    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function activeRooms()
    {
        return $this->hasMany(Room::class)->active();
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }

    public function bookedRooms()
    {
        return $this->hasMany(BookedRoom::class)->active();
    }
    public function scopeActive($query)
    {
        return $query->where('room_types.status', Status::ROOM_ACTIVE);
    }
    //scope
    // public function scopeFeatured($query)
    // {
    //     return $query->where('is_featured', Status::ROOM_TYPE_FEATURED);
    // }

    public function featureBadge(): Attribute
    {
        return new Attribute(
            function () {
                $html = '';

                if ($this->is_featured == Status::ROOM_TYPE_FEATURED) {
                    $html = '<span class="badge badge--primary">' . trans('Nổi bật') . '</span>';
                    //Featured
                } else {
                    $html = '<span><span class="badge badge--dark">' . trans('Không có gì nổi bật') . '</span></span>';
                }   //Unfeatured

                return $html;
            }
        );
    }

    public function roomTypePrice()
    {
             $today = Carbon::now();

            Log::info('Hôm nay là ngày: ' . $today->toDateString());
            Log::info('Hôm nay là thứ: ' . $today->translatedFormat('l')); 

           return $this->hasOne(RoomTypePrice::class, 'room_type_id', 'id')
            ->where('price_validity_period', '<=', now()->toDateString())
            ->orderByDesc('price_validity_period')
            ->with('setupPricing');

        // $today = Carbon::now()->toDateString();
        // $weekday = (string)(Carbon::now()->dayOfWeekIso + 1);
        // if ($weekday == '9') $weekday = '2';

        // return $this->hasMany(RoomTypePrice::class, 'room_type_id', 'id')
        //     ->where('price_validity_period', '<=', $today)
        //     ->whereHas('setupPricing', function ($query) use ($weekday, $today) {
        //         $query->where(function ($q) use ($weekday, $today) {
        //             $q->whereJsonContains('price_requirement', $weekday)
        //                 ->orWhereJsonContains('price_requirement', $today);
        //         });
        //     })
        //     ->with(['setupPricing' => function ($q) use ($weekday, $today) {
        //         $q->where(function ($q2) use ($weekday, $today) {
        //             $q2->whereJsonContains('price_requirement', $weekday)
        //                 ->orWhereJsonContains('price_requirement', $today);
        //         });
        //     }]);
    }
}
