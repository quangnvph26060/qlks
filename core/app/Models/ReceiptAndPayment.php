<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'unit_code',
        'room_code',
        'status',
        'payment_id',
    ];
    protected $appends = ['status_badge','check_in'];

    public function getDueAttribute()
    {
        return $this->room_price  + $this->service_fee - $this->deposit_amount - $this->discount_amount - $this->total_payment;
    }
    public function statusBadge(): Attribute
    {
        $className = 'badge badge--';
        if ($this->status == Status::DISABLE) {
            $className .= 'warning';
            $text = 'Chưa xử lý';
        } elseif ($this->status == Status::ENABLE) {
            $className .= 'success';
            $text = 'Thành công';
        }
        return new Attribute(
            get: fn() => "<span class='badge badge--$className'>" . trans($text) . "</span>",
        );
    }
    public function checkIn(): Attribute
    {
        return Attribute::get(function () {
            return CheckIn::where('check_in_id', $this->checkin_id)
                ->where('room_change', $this->room_code)
                ->where('unit_code', unitCode())
                ->first()
                ?? CheckIn::where('check_in_id', $this->checkin_id)
                    ->where('room_code', $this->room_code)
                    ->where('unit_code', unitCode())
                    ->first();
        });
        
    }

}
