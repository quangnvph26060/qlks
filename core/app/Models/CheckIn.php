<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    use HasFactory;
    protected $table = 'check_in'; // Tên bảng trong cơ sở dữ liệu

    protected $primaryKey = 'id'; // Khóa chính

    public $incrementing = true; // Khóa chính tự động tăng

    protected $keyType = 'int'; // Loại của khóa chính

    public $timestamps = true; // Sử dụng `created_at` và `updated_at`

    protected $fillable = [
        'check_in_id',
        'id_room_booking',
        'room_code',
        'document_date',
        'checkin_date',
        'checkout_date',
        'customer_code',
        'customer_name',
        'phone_number',
        'email',
        'price_group',
        'guest_count',
        'total_amount',
        'deposit_amount',
        'note',
        'user_source',
        'unit_code',
        'created_by',
    ];
    public function room() {
        return $this->belongsTo(Room::class,'room_code');
    }

    public function admin() {
        return $this->belongsTo(Admin::class,'created_by');
    }
}
