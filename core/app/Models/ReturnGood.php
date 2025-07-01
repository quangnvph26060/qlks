<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
class ReturnGood extends Model
{
    use HasFactory,BelongsToTenant;

    protected $table = 'returns';

    protected $fillable = [
        'warehouse_entry_id',
        'reference_code',
        'status',
        'total',
        'subdomain',
        'unit_code',
    ];


    public function warehouse_entry()
    {
        return $this->belongsTo(WarehouseEntry::class, 'warehouse_entry_id', 'id');
    }

    public function return_items()
    {
        return $this->belongsToMany(Product::class, 'return_entries', 'return_id', 'product_id')->withPivot('quantity', 'reason');
    }

    protected $cats = [
        'status' => 'boolean',
    ];
}
