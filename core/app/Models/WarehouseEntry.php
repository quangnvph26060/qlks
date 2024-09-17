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
        'status'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function entries()
    {
        return $this->hasMany(WarehouseEntryItem::class);
    }

    public function payments()
    {
        return $this->hasOne(Transaction::class);
    }

}
