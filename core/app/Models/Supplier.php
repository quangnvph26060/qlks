<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'address', 'bank_id', 'is_active', 'account_number', 'tax_code', 'supplier_id'];


    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_supplier', 'supplier_id', 'product_id');
    }

    public function supplier_representatives()
    {
        return $this->hasMany(SupplierRepresentative::class, 'supplier_id', 'id');
    }
}
