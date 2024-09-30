<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class RoomType extends Model
{
    use GlobalStatus;
    protected $casts = [
        'keywords' => 'array',
        'beds'     => 'array'
    ];

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
