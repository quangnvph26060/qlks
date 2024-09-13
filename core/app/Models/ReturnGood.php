<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnGood extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'supplier_id',
        'product_id',
        'reference_code',
        'quantity',
        'reason',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

}
