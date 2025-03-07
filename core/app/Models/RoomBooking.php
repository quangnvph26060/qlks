<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomBooking extends Model
{
    use HasFactory;
    protected $table = 'room_booking';
    protected $appends = ['room_change_info'];

    /**
     * The primary key associated with the table.
     *
     * @var string
     */

    protected $primaryKey = 'id';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id',
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
        'status',
        'discount',
        'room_change',
    ];
     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'document_date' => 'date',
    //     'checkin_date' => 'date',
    //     'checkout_date' => 'date',
    //     'total_amount' => 'decimal:2',
    //     'deposit_amount' => 'decimal:2',
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime',
    // ];
    public function room() {
        return $this->belongsTo(Room::class,'room_code');
    }
    // public function actualRoom() {
    //     return $this->belongsTo(Room::class, 'room_change');
    // }
    
    // public function defaultRoom() {
    //     return $this->belongsTo(Room::class, 'room_code');
    // }
    // public function getRoomAttribute() {
    //     return $this->actualRoom ?? $this->defaultRoom;
    // }
    
    public function getRoomChangeInfoAttribute() {
        if($this->room_change != null) {
            return RoomChange::where('id_room_booking', $this->booking_id)
                ->where('new_room_code', $this->room_change)->with('room') ->orderBy('updated_at', 'desc')
                ->first();
        }
    }
    
    
    
    
    
    public function admin() {
        return $this->belongsTo(Admin::class,'created_by');
    }
    //scope
    public function scopeActive() {
        return $this->where('status', 0);
    }
    public function due()
    {
        return $this->total_amount - $this->deposit_amount;
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_code', 'customer_code');
    }
    public function  roomBookingChange(){
        return $this->hasOne(RoomChange::class, 'id_room_booking', 'booking_id')
        ->latest('created_at');   
    }

}
