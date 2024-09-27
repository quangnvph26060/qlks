<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPrice extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    protected $fillable = [
        'code',
        'name',
        'price',
        'start_date',
        'end_date',
        'status',
        'start_time',
        'end_time',
        'specific_date'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }
}
