<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class WarehouseEntryLog extends Model
{
    protected $table = 'warehouse_entry_logs';

    protected $fillable = [
        'warehouse_entry_id',
        'user_id',
        'action',
    ];

    /**
     * Phiếu nhập liên quan
     */
    public function warehouseEntry(): BelongsTo
    {
        return $this->belongsTo(WarehouseEntry::class);
    }

    /**
     * Người thực hiện hành động
     */
    public function admin(): BelongsTo
    {
      return $this->belongsTo(Admin::class, 'user_id', 'id');

    }
}
