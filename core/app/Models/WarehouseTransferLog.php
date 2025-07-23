<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class WarehouseTransferLog extends Model
{
    protected $fillable = [
        'warehouse_transfer_id',
        'user_id',
        'action',
    ];

    public function transfer(): BelongsTo
    {
        return $this->belongsTo(WarehouseTransfer::class, 'warehouse_transfer_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id'); // hoặc Admin nếu bạn dùng model Admin
    }
}
