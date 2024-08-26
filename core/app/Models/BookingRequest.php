<?php

namespace App\Models;

use App\Constants\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BookingRequest extends Model {

    protected $fillable = ['id'];

    protected $casts = [
        'room_type_details' => 'object'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function roomType() {
        return $this->belongsTo(RoomType::class);
    }

    //scope
    public function scopeInitial($query) {
        return $query->where('status', Status::BOOKING_REQUEST_PENDING);
    }

    public function scopeApproved($query) {
        return $query->where('status', Status::BOOKING_REQUEST_APPROVED);
    }

    public function scopeCanceled($query) {
        return $query->where('status', Status::BOOKING_REQUEST_CANCELED);
    }

    public function statusBadge(): Attribute {
        $className = 'badge badge--';
        if ($this->status == Status::BOOKING_REQUEST_PENDING) {
            $className .= 'warning';
            $text = 'Pending';
        } elseif ($this->status == Status::BOOKING_REQUEST_APPROVED) {
            $className .= 'success';
            $text = 'Approved';
        } elseif ($this->status == Status::BOOKING_REQUEST_CANCELED) {
            $className .= 'danger';
            $text = 'Canceled';
        }

        return new Attribute(
            get: fn () => "<span class='badge badge--$className'>" . trans($text) . "</span>",
        );
    }

    function bookFor() {
        return Carbon::parse($this->check_in)->diffInDays(Carbon::parse($this->check_out));
    }

    public function totalFare() {
        return $this->total_amount - $this->tax_charge;
    }

    public function taxPercentage() {
        return $this->tax_charge * 100 / ($this->totalFare() > 0 ? $this->totalFare() : 1);
    }

    public function taxCharge() {
        return $this->unit_fare * $this->taxPercentage() / 100;
    }
}
