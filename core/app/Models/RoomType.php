<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class RoomType extends Model
{
    use GlobalStatus;

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
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', Status::ROOM_TYPE_FEATURED);
    }

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
}
