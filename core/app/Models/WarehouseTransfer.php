<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseTransfer extends Model
{
    protected $table = 'warehouse_transfers';

    protected $fillable = [
        'reference_code',
        'from_warehouse_id',
        'to_warehouse_id',
        'export_id',
        'entry_id',
        'transfer_date',
        'subdomain',
        'unit_code',
        'note',
        'created_by',
        'status',
    ];

    protected $dates = ['transfer_date'];

    // Các quan hệ liên kết nếu cần
    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }

    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }

    public function export()
    {
        return $this->belongsTo(WarehouseExport::class, 'export_id','reference_code');
    }

    public function entry()
    {
        return $this->belongsTo(WarehouseEntry::class, 'entry_id','reference_code');
    }
}
