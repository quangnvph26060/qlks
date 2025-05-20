<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
class RoomChange extends Model
{
    use HasFactory,BelongsToTenant;

    protected $table = 'room_change';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'room_change_id',
        'id_room_booking',
        'id_check_in',
        'old_room_code',
        'new_room_code',
        'document_date',
        'discount',
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
        'created_by',
        'unit_code',
    ];
    public function room() {
        return $this->belongsTo(Room::class,'new_room_code');
    }
    public function roomNew() {
        return $this->belongsTo(Room::class,'new_room_code');
    }
    public function roomOld() {
        return $this->belongsTo(Room::class,'old_room_code');
    }
    public function admin() {
        return $this->belongsTo(Admin::class,'created_by');
    }
}
