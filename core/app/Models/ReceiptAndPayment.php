<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\BelongsToTenant;

class ReceiptAndPayment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'receipts_and_payments';

    protected $fillable = [
        'booking_id',
        'checkin_id',
        'room_price',
        'payment_method',
        'created_date',
        'unit_code',
        'status',
        'payment_id',
        'subdomain',
        'creator'
    ];
    protected $appends = ['status_badge', 'check_in', 'room_booking', 'creator_name', 'service_booking'];

    public function getDueAttribute()
    {
        return $this->room_price  + $this->service_fee - $this->deposit_amount - $this->discount_amount - $this->total_payment;
    }
    public function creatorName(): Attribute
    {
        return new Attribute(
            get: fn() => Admin::find($this->creator)->name,
        );
    }
    public function statusBadge(): Attribute
    {
        $className = 'badge badge--';
        if ($this->status == Status::DISABLE) {
            $className .= 'warning';
            $text = 'Đang xử lý';
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
            return CheckIn::with('room')->where('check_in_id', $this->checkin_id)
                ->get()
                ?? CheckIn::with('room')->where('check_in_id', $this->checkin_id)
                ->get();
        });
    }
    public function roomBooking(): Attribute
    {
        return Attribute::get(function () {
            return RoomBooking::with('room')->where('booking_id', $this->booking_id)
                ->get();
        });
    }
    public function serviceBooking(): Attribute
    {
        return Attribute::get(function () {
            return RoomServiceProduct::with('service','product')->where('check_in_id', $this->checkin_id)
                ->get();
        });
    }
    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class, 'receipts_and_payments_id');
    }
}
