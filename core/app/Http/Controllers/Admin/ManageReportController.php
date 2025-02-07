<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomStatus;
use App\Models\RoomStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ManageReportController extends Controller
{

    public function rommStatus(Request $request)
{
    $pageTitle = 'Báo cáo trạng thái phòng';
    $keyword = $request->input('keyword');

    // Truy vấn kết hợp bảng rooms và room_status
    $roomStatusHistory = RoomStatusHistory::query()
    ->where('room_status_history.unit_code', unitCode())
        ->join('rooms', 'room_status_history.room_id', '=', 'rooms.id')
        ->join('room_status', 'room_status_history.status_code', '=', 'room_status.id')
        ->when($keyword, function ($query) use ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->orWhere('rooms.room_number', 'like', '%' . $keyword . '%')
                      ->orWhere('room_status.status_name', 'like', '%' . $keyword . '%');
            });
        })
        ->orderBy('room_status_history.created_at', 'desc')
        ->select('room_status_history.*', 'rooms.room_number as room_name', 'room_status.status_name as status_name') // Chọn thêm tên phòng & trạng thái
        ->paginate(getPaginate());

    return view('admin.manage-room-status.index', compact('pageTitle', 'roomStatusHistory', 'keyword'));
}

}
