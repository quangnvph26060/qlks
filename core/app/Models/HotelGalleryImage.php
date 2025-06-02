<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelGalleryImage extends Model
{
     use HasFactory;

    protected $table = 'hotel_gallery_images';

    protected $fillable = [
        'hotel_facility_id',
        'image_url',
        // nếu có thêm cột khác bạn thêm vào đây
    ];

    // Quan hệ ngược lại: 1 ảnh thuộc về 1 hotel facility
    public function hotelFacility()
    {
        return $this->belongsTo(HotelFacility::class, 'hotel_facility_id', 'id');
    }
}
