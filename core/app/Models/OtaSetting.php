<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class OtaSetting extends Model
{
    protected $table = 'ota_settings';

    protected $fillable = [
        'hotel_id',
        'ota_id',
        'discount_code',
        'allow_all_rooms',
        'allowed_room_types',
        'allowed_rooms',
        'status',
        'subdomain',
    ];
    protected $appends = ['status_badge'];
    protected $casts = [
        'allowed_room_types' => 'array',
        'allowed_rooms' => 'array',
    ];
    public function hotelFacility()
    {
        return $this->belongsTo(HotelFacility::class, 'hotel_id', 'id');
    }
    public function ota()
    {
        return $this->belongsTo(Ota::class, 'ota_id');
    }
     public function statusBadge(): Attribute
    {
        $className = 'badge badge--';
        if ($this->status == Status::DISABLE) {
            $className .= 'warning';
            $text = 'Không kích hoạt';
        } elseif ($this->status == Status::ENABLE) {
            $className .= 'success';
            $text = 'Kích hoạt';
        }
        return new Attribute(
            get: fn() => "<span class='badge badge--$className'>" . trans($text) . "</span>",
        );
    }
}
