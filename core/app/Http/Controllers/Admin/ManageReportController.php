<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\RoomStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ManageReportController extends Controller
{

    public function rommStatus(Request $request)
    {
        $pageTitle = 'Báo cáo trạng thái phòng';
        $roomStatus = RoomStatus::all();   
        $room       = Room::active()->get();
        return view('admin.manage-room-status.index', compact('pageTitle','roomStatus', 'room'));
    }
    public function roomStatusHistory(Request $request){
        $perPage = getPaginate();
        $room_id = $request->room_id;
        $status_id = $request->status_id;
        // Truy vấn kết hợp bảng rooms và room_status
        $roomStatusHistory = RoomStatusHistory::query()
        ->where('room_status_history.unit_code', unitCode())
            ->join('rooms', 'room_status_history.room_id', '=', 'rooms.id')
            ->join('room_status', 'room_status_history.status_code', '=', 'room_status.id')
            
            ->when(!empty($room_id), function ($query) use ($room_id) {
                $query->where('rooms.id', $room_id);
            })
            ->when(!empty($status_id), function ($query) use ($status_id) {
                $query->where('room_status.id', $status_id);
            })
            ->orderBy('room_status_history.created_at', 'desc')
            ->select('room_status_history.*', 'rooms.room_number as room_name', 'room_status.status_name as status_name') // Chọn thêm tên phòng & trạng thái
            ->paginate($perPage);
          
        return response()->json([
                'status'  =>'success',
                'data' => $roomStatusHistory->items(),
                'pagination' => [
                    'total' => $roomStatusHistory->total(),
                    'current_page' => $roomStatusHistory->currentPage(),
                    'last_page' => $roomStatusHistory->lastPage(),
                    'per_page' => $roomStatusHistory->perPage(),
                ]
            ]);
        
      
    }

}
