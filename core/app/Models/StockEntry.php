<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    use HasFactory, GlobalStatus;

    public function warehouse()
    {
        return $this->belongsTo(WarehouseEntry::class, 'warehouse_entry_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
