<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customer';

    protected $fillable = [
        'customer_code',
        'name',
        'phone',
        'email',
        'address',
        'note',
        'unit_code',
        'status',
        'group_code'
    ];
}
