<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseEntryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_entry_id',
        'product_id',
        'quantity',
    ];

    public function warehouseEntry()
    {
        return $this->belongsTo(WarehouseEntry::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
