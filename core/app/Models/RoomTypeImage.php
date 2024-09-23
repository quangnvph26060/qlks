<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class RoomTypeImage extends Model
{
    use GlobalStatus;
    protected $casts = [
        'keywords' => 'array',
        'beds' => 'array'
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }
}
