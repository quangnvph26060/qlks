<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomMaintenance extends Model
{
    // Tên bảng nếu không theo quy tắc số nhiều mặc định
    protected $table = 'room_maintenance';

    // Các trường có thể gán hàng loạt
    protected $fillable = [
        'room_id',
        'fix_date',
        'admin_id',
        'unit_code',
        'subdomain',
    ];


    // Quan hệ với bảng phòng (rooms)
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    // Quan hệ với bảng nhân viên (admins)
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
