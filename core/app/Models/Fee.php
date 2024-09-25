<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $table = 'fees';
    protected $fillable = [
        'per_hour',
        'per_day',
        'per_night',
        'per_season',
        'per_event',
    ];
}
