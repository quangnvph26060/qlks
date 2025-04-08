<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptAndPayment extends Model
{
    use HasFactory;

    protected $table = 'receipts_and_payments';

    protected $fillable = [
        'booking_id',
        'checkin_id',
        'room_price',
        'deposit_amount',
        'discount_amount',
        'service_fee',
        'product_price',
        'total_payment',
        'payment_method',
        'created_date',
        'unit_code'
    ];

}
