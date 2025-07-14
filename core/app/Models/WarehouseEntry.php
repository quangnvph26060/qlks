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
        'created_by',
        'note',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function returns()
    {
        return $this->hasMany(ReturnGood::class);
    }

    public function entries()
    {
        return $this->hasMany(WarehouseEntryItem::class,'warehouse_entry_id');
    }

    public function payments()
    {
        return $this->hasOne(Transaction::class);
    }

    public function admin() {
        return $this->belongsTo(Admin::class,'created_by');
    }
    public function stockEntries()
    {
       // return $this->belongsToMany(Product::class, 'stock_entries', 'product_id')->withPivot('quantity', 'entry_date')->withTimestamps();
       return $this->belongsToMany(Product::class, 'stock_entries', 'warehouse_entry_id', 'product_id')
    ->withPivot('quantity', 'entry_date')
    ->withTimestamps();

    }
}
