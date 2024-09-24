<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    public $timestamps = false;
    protected $table = 'transactions';
    protected $fillable = [
        'name',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse_entry()
    {
        return $this->belongsTo(WarehouseEntry::class);
    }


    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
