<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class WarehouseExportLog extends Model
{
    protected $fillable = [
        'warehouse_export_id',
        'user_id',
        'action',
    ];

     public function admin(): BelongsTo
    {
      return $this->belongsTo(Admin::class, 'user_id', 'id');

    }

    public function export(): BelongsTo
    {
        return $this->belongsTo(WarehouseExport::class, 'warehouse_export_id', 'id');
    }
}
