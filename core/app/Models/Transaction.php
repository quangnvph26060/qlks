<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = ['warehouse_entry_id ', 'payment_method_id', 'amount', 'transaction_date'];


    public function warehouse_entry()
    {
        return $this->belongsTo(WarehouseEntry::class);
    }


    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

}
