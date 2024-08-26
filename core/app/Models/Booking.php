<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Booking extends Model
{

    protected $casts = [
        'guest_details' => 'object',
        'checked_out_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookingRequest()
    {
        return $this->hasOne(BookingRequest::class);
    }

    public function approvedBy()
    {
        return $this->hasOne(BookingActionHistory::class)->where('remark', 'approve_booking_request');
    }

    public function bookedBy()
    {
        return $this->hasOne(BookingActionHistory::class)->where('remark', 'book_room');
    }

    public function checkedOutBy()
    {
        return $this->hasOne(BookingActionHistory::class)->where('remark', 'checked_out');
    }

    public function canceledBy()
    {
        return $this->hasOne(BookingActionHistory::class)->where('remark', 'cancel_booking');
    }

    public function bookedRooms()
    {
        return $this->hasMany(BookedRoom::class, 'booking_id');
    }

    public function activeBookedRooms()
    {
        return $this->hasMany(BookedRoom::class, 'booking_id')->where('status', Status::ROOM_ACTIVE);
    }

    public function usedPremiumService()
    {
        return $this->hasMany(UsedPremiumService::class);
    }

    public function payments()
    {
        return $this->hasMany(PaymentLog::class);
    }

    //scope
    public function scopeActive($query)
    {
        return $query->where('status', Status::BOOKING_ACTIVE);
    }

    public function scopeCheckedOut($query)
    {
        return $query->where('status', Status::BOOKING_CHECKOUT);
    }

    public function scopeDelayedCheckout($query)
    {
        $query->active()->where(function ($booking) {
            $booking->where(function ($booking) {
                $booking->whereDate('check_out', '<', now());
            })->orWhere(function ($booking) {
                $booking->whereDate('check_out', '=', now())
                    ->where(function ($booking) {
                        if (date('H:i:s') > gs()->checkout_time) {
                            return $booking;
                        } else {
                            return $booking->where('id', '0');
                        }
                    });
            });
        });
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', Status::BOOKING_CANCELED);
    }

    public function scopeTodayCheckIn($query)
    {
        return $query->whereDate('check_in', now());
    }

    public function scopeTodayCheckout($query)
    {
        return $query->whereDate('check_out', now());
    }

    public function scopeRefundable($query)
    {
        return $query->canceled()->whereRaw('(booking_fare + tax_charge + service_cost + extra_charge + cancellation_fee - extra_charge_subtracted - paid_amount) < 0');
    }

    public function scopeKeyGiven($query)
    {
        return $query->where('key_status', Status::KEY_GIVEN);
    }

    public function scopeKeyNotGiven($query)
    {
        return $query->where('key_status', Status::KEY_NOT_GIVEN);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(
            function () {
                if (now() >= $this->check_in && $this->status == Status::BOOKING_ACTIVE) {
                    $class = "badge--success";
                    $text = 'Running';
                } elseif (now() < $this->check_in && $this->status == Status::BOOKING_ACTIVE) {
                    $class = "badge--warning";
                    $text = 'Upcoming';
                } elseif ($this->status == Status::BOOKING_CANCELED) {
                    $class = "badge--danger";
                    $text = 'Canceled';
                } else {
                    $class = "badge--dark";
                    $text = 'Checked Out';
                }

                $html = "<small class='badge $class'>" . trans($text) . "</small>";
                return $html;
            }
        );
    }

    public function totalAmount(): Attribute
    {
        return new Attribute(
            function () {
                return getAmount($this->booking_fare + $this->tax_charge + $this->service_cost + $this->extra_charge + $this->cancellation_fee - $this->extra_charge_subtracted);
            }
        );
    }

    public function due()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function isDelayed()
    {
        $currentDate = date('Y-m-d');
        $currentTime = date('H:i:s');
        if ($this->status == Status::BOOKING_ACTIVE && ($this->check_out < $currentDate || ($this->check_out == $currentDate && $currentTime > gs()->checkout_time))) {
            return 1;
        } else {
            return 0;
        }
    }

    public function extraCharge()
    {
        return $this->extra_charge - $this->extra_charge_subtracted;
    }

    public function taxPercentage()
    {
        return $this->tax_charge * 100 / ($this->booking_fare > 0 ? $this->booking_fare : 1);
    }

    public function createActionHistory($remark, $details = null)
    {
        $bookingActionHistory             = new BookingActionHistory();
        $bookingActionHistory->booking_id = $this->id;
        $bookingActionHistory->remark     = $remark;
        $bookingActionHistory->details    = $details;
        $bookingActionHistory->admin_id   = authAdmin()->id;
        $bookingActionHistory->save();
    }

    public function createPaymentLog($amount, $type, $isUser = false)
    {
        $paymentLog             = new PaymentLog();
        $paymentLog->booking_id = $this->id;
        $paymentLog->amount     = $amount;
        $paymentLog->type       = $type;
        $paymentLog->admin_id   = $isUser ? 0 : authAdmin()->id;
        $paymentLog->save();
    }
}
