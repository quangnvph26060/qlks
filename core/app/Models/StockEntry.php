<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    use HasFactory;

    public function warehouse()
    {
        return $this->belongsTo(WarehouseEntry::class, 'warehouse_entry_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
