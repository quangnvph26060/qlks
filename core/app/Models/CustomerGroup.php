<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    use HasFactory;
    protected $table = 'customer_groups';
    protected $fillable = [
        'group_code',
        'group_name',
        'unit_code',
        'created_at',
        'updated_at',
    ];
}
