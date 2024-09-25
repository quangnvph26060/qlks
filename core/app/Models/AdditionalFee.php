<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalFee extends Model
{
    use HasFactory;

    protected $table = 'additional_fees';
    protected $fillable = [
        'early_checkout',
        'late_checkout',
        'none_checkin',
        'cancellation',
        'extra_bed',
        'early_checkin'
    ];
}
