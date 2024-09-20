<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'reference_code',
        'total',
        'status',
        'confirmation_date'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function return()
    {
        return $this->hasOne(ReturnGood::class);
    }

    public function entries()
    {
        return $this->hasMany(WarehouseEntryItem::class);
    }

    public function payments()
    {
        return $this->hasOne(Transaction::class);
    }

    public function stockEntries()
    {
        return $this->belongsToMany(Product::class, 'stock_entries', 'warehouse_entry_id', 'product_id')->withPivot('quantity', 'entry_date')->withTimestamps();
    }
}
