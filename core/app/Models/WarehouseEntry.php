<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
class WarehouseEntry extends Model
{
    use HasFactory,BelongsToTenant;

    protected $fillable = [
        'supplier_id',
        'reference_code',
        'total',
        'status',
        'confirmation_date',
        'unit_code',
        'subdomain',
        'created_time',
        'payment_method_id',
        'created_by'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function return()
    {
        return $this->hasMany(ReturnGood::class);
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
        return $this->belongsToMany(Product::class, 'stock_entries', 'product_id')->withPivot('quantity', 'entry_date')->withTimestamps();
    }
}
