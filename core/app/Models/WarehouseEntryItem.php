<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseEntryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_entry_id',
        'warehouse_export_id',
        'product_id',
        'quantity',
        'warehouse_id',
        'type',
        'price',

    ];

    public function warehouseEntry()
    {
        return $this->belongsTo(WarehouseEntry::class);
    }
    public function warehouseExport()
    {
        return $this->belongsTo(WarehouseExport::class, 'warehouse_export_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
