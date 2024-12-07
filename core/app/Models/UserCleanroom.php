<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCleanroom extends Model
{
    use HasFactory;
    protected $table = 'user_cleanroom';
   
    protected $fillable = [
        'room_id',
        'clean_date',
        'admin_id',
    ];
    
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }
    
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }
}
