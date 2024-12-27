<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelFacility extends Model
{
    use HasFactory;

    protected $table = 'hotel_facilities';
    protected $fillable = [
        'ma_coso', 
        'ten_coso',
        'trang_thai', 
    ];
    
    protected static function boot()
    {
        parent::boot();
        static::saved(function(){
            \Cache::forget('HotelFacility');
        });
    }
}
