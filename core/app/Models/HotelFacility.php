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
    public function styleStatus(){
        return $this->trang_thai == 1 ? '<span class="badge badge--success">Hoạt động</span>' : '<span class="badge badge--danger">Không hoạt động</span>';
    }
    protected static function boot()
    {
        parent::boot();
        static::saved(function(){
            \Cache::forget('HotelFacility');
        });
    }
    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 1);
    }
}
