<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class SetupPricing extends Model
{
    use HasFactory,BelongsToTenant;

    protected $table = 'setup_pricing'; 

    protected $fillable = [
        'price_code',
        'price_name',
        'price_requirement',
        'check_in_time',
        'check_out_time',
        'round_time',
        'description',
        'unit_code',
        'subdomain',
    ];
}
