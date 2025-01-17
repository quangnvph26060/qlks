<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSource extends Model
{
    use HasFactory;

    protected $table = 'customer_sources';

    protected $fillable = [
        'source_code',
        'source_name',
        'unit_code',
        'created_at',
        'updated_at',
    ];
}
