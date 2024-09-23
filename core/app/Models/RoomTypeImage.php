<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomTypeImage extends Model
{

    use GlobalStatus;   
    use HasFactory;
    protected $casts = [
        'keywords' => 'array',
        'beds' => 'array'
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }




}
