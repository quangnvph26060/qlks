<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HotelFacility;
use App\Models\OtaSetting;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;

class OTAController extends Controller
{
    public function index()
    {
        $hotels    = HotelFacility::where('subdomain', subdomain())->first();

        // room types
        $room_type = RoomType::all();

        // rooms 
        $rooms = RoomType::whereHas('rooms')->with('rooms')->get();

        $ota = OtaSetting::where('subdomain', subdomain())->first();
        return view('admin.hotel.ota.index', compact('hotels', 'room_type', 'rooms', 'ota'));
    }
    public function save(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotel_facilities,id', // ✅ Đúng tên bảng ở đây
            'ota_case' => 'required|in:1,2,3',
        ]);

        try {
            $hotelId = $request->input('hotel_id');
            $case = $request->input('ota_case');

            $data = [
                'hotel_id' => $hotelId,
                'allow_all_rooms' => $case == 1 ? 1 : 0,
                'allowed_room_types' => $case == 2 ? $request->input('room_types', []) : [],
                'allowed_rooms' => $case == 3 ? $request->input('rooms', []) : [],
                'status'       =>  $request->status ?? 0,
                'subdomain'    => subdomain(),
            ];

            OtaSetting::updateOrCreate(
                ['hotel_id' => $hotelId],
                $data
            );

            $notify[] = ['success', 'Thêm thành công'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            // Bạn có thể log lỗi để dễ dàng debug
            \Log::error('Lỗi khi lưu OtaSetting: ' . $e->getMessage());

            $notify[] = ['error', 'Có lỗi xảy ra, vui lòng thử lại sau'];
            return back()->withNotify($notify);
        }
    }
}
