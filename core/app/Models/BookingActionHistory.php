<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
class BookingActionHistory extends Model {
    use BelongsToTenant;
    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}
