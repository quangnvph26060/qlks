<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class RoomType extends Model {
    use GlobalStatus;
    protected $casts = [
        'keywords' => 'array',
        'beds'     => 'array'
    ];

    public function amenities() {
        return $this->belongsToMany(Amenity::class, 'room_type_amenities', 'room_type_id', 'amenities_id')->withTimestamps();
    }

    public function facilities() {
        return $this->belongsToMany(Facility::class, 'room_type_facilities', 'room_type_id', 'facility_id')->withTimestamps();
    }

    public function rooms() {
        return $this->hasMany(Room::class);
    }

    public function activeRooms() {
        return $this->hasMany(Room::class)->active();
    }

    public function images() {
        return $this->hasMany(RoomTypeImage::class);
    }

    public function bookedRooms() {
        return $this->hasMany(BookedRoom::class)->active();
    }

    //scope
    public function scopeFeatured($query) {
        return $query->where('is_featured', Status::ROOM_TYPE_FEATURED);
    }

    public function featureBadge(): Attribute {
        return new Attribute(
            function () {
                $html = '';

                if ($this->is_featured == Status::ROOM_TYPE_FEATURED) {
                    $html = '<span class="badge badge--primary">' . trans('Featured') . '</span>';
                } else {
                    $html = '<span><span class="badge badge--dark">' . trans('Unfeatured') . '</span></span>';
                }

                return $html;
            }
        );
    }
}
