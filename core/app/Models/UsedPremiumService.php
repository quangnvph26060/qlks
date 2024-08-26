<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsedPremiumService extends Model
{
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function premiumService()
    {
        return $this->belongsTo(PremiumService::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function bookedRoom()
    {
        return $this->belongsTo(Room::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
