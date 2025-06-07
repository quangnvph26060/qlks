<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HotelConfiguration extends Model
{
    protected $table = 'hotel_configurations';
    protected $fillable = [
        'hotel_facility_id',
        'hotel_name',
        'slug',
        'main_image',
        'logo',
        'address',
        'phone',
        'external_link',
        'icon',
        'latitude',
        'longitude',
        'province',
        'email',
        'chinh_sach',
        'gioi_thieu'
    ];
    protected $hidden = [
        'created_at','updated_at'
    ];
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->hotel_name);
            }
        });
    }


    // Quan hệ 1-1 với HotelFacility
    public function hotelFacility()
    {
        return $this->belongsTo(HotelFacility::class, 'hotel_facility_id', 'id');
    }
    public function galleryImages()
    {
        return $this->hasManyThrough(
            HotelGalleryImage::class,
            HotelFacility::class,
            'id',               // Khóa chính ở bảng trung gian (hotel_facilities)
            'hotel_facility_id', // Khóa ngoại ở bảng cuối (hotel_gallery_images)
            'hotel_facility_id', // Khóa ngoại ở model hiện tại (hotel_configurations)
            'id'                // Khóa chính ở bảng trung gian (hotel_facilities)
        );
    }
}
