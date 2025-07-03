<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use App\Traits\GlobalStatus;
class Warehouse extends Model
{
     use BelongsToTenant,GlobalStatus;
    protected $table = 'warehouses';

    protected $fillable = [
        'code',
        'name',
        'status',
        'unit_code',
        'subdomain',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
