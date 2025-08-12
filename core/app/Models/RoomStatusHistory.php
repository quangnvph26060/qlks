<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use Illuminate\Support\Facades\DB;

class RoomStatusHistory extends Model
{
    use BelongsToTenant;
    protected $table = 'room_status_history';

    protected $fillable = [
        'room_id',
        'status_code',
        'start_date',
        'end_date',
        'unit_code',
        'subdomain'
    ];
    public function roomStatus()
    {
        return $this->hasOne(RoomStatus::class, 'id', 'status_code');
    }

    public function room()
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }
    public function bookingData()
    {
        return $this->hasMany(RoomBooking::class, 'checkin_date', 'start_date')
            ->join('room_status_history', function ($join) {
                $join->on('room_status_history.end_date', '=', 'room_booking.checkout_date')
                    ->whereColumn('room_booking.room_code', 'room_status_history.room_id');
            })
            ->select('room_booking.*');
    }


    public function checkInData()
    {
        return $this->hasMany(CheckIn::class, 'checkin_date', 'start_date')
            ->join('room_status_history', function ($join) {
                $join->on('room_status_history.end_date', '=', 'check_in.checkout_date')
                    //  ->whereColumn('check_in.room_change', 'room_status_history.room_id');
                    ->where(function ($query) {
                        $query->whereNotNull('check_in.room_change') // Nếu room_change có giá trị
                            ->whereColumn('room_status_history.room_id', '=', 'check_in.room_change');
                    })
                    // Nếu room_change là null, so sánh với room_code
                    ->orWhere(function ($query) {
                        $query->whereNull('check_in.room_change')  // Nếu room_change là null
                            ->whereColumn('room_status_history.room_id', '=', 'check_in.room_code'); // So sánh với room_code
                    });
            })
            ->leftJoin('receipts_and_payments', 'receipts_and_payments.checkin_id', '=', 'check_in.check_in_id')
            // Join tiếp sang bảng payments
            ->leftJoin('payment_transactions', 'payment_transactions.receipts_and_payments_id', '=', 'receipts_and_payments.id')
           ->select(
    'check_in.*',
    DB::raw('
        (
            SELECT SUM(pt.amount)
            FROM receipts_and_payments rp
            LEFT JOIN payment_transactions pt
                ON pt.receipts_and_payments_id = rp.id
            WHERE rp.checkin_id = check_in.check_in_id
        ) as total_amount_paid
    ')
);
    }
}
