<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierRepresentative extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'position', 'supplier_id'];
}
