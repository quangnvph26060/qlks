<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelFacility;
use App\Models\OtaSetting;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApipublicController extends Controller
{
    protected  function Isubdomain()
    {
        $host = request()->getHost();
        return explode('.', $host)[0];
    }

    public function getRooms(Request $request)
    {
        try {
            $otaSetting = OtaSetting::where('subdomain', $this->Isubdomain())->first();
            if (!$otaSetting || $otaSetting->status != 1) {
                return response()->json([
                    'message' => 'Không có quyền truy cập'
                ], 401);
            }

            $date = $request->input('date', Carbon::today()->toDateString());

            $rooms = Room::withoutTenant()->where('subdomain', $this->Isubdomain())->active();
            $rooms->select('id', 'room_number', 'room_type_id', 'is_clean', 'total_adult', 'total_child', 'beds', 'description');

            if (!$otaSetting->allow_all_rooms) {
                $filtered = false;

                // Ưu tiên lọc theo ID phòng nếu có
                $allowedRooms = $otaSetting->allowed_rooms;
                if (is_array($allowedRooms) && count($allowedRooms)) {
                    $rooms->whereIn('id', $allowedRooms);
                    $filtered = true;
                }

                if (!$filtered) {
                    $allowedRoomTypes = is_string($otaSetting->allowed_room_types)
                        ? json_decode($otaSetting->allowed_room_types, true)
                        : [];

                    if (is_array($allowedRoomTypes) && count($allowedRoomTypes)) {
                        $rooms->whereIn('room_type_id', $allowedRoomTypes);
                    }
                }
            }

            $rooms->with([
                'roomType' => function ($query) {
                    $query->select('id', 'name', 'main_image', 'slug');
                },
                'roomType.roomTypePrice' => function ($query) {
                    $query->select('room_type_id', 'unit_price', 'overtime_price', 'extra_person_price');
                },
                'roomBookingHistory' => function ($query) use ($date) {
                    if (!empty($date)) {
                        $query->whereDate('start_date', '<=', $date)
                            ->whereDate('end_date', '>', Carbon::parse($date)->subDay())
                            ->select('room_id', 'status_code', 'start_date', 'end_date');
                    }
                },
                'roomBookingHistory.roomStatus',
                // 'roomBookingHistory.checkInData',
                // 'roomBookingHistory.bookingData',
            ]);

            $rooms = $rooms->get();

            $hotel = HotelFacility::where('id', $otaSetting->hotel_id)
                ->where('trang_thai', 1)
                ->select('ten_coso', 'ma_coso')
                ->first();

            return response()->json([
                'hotel' => $hotel,
                'rooms' => $rooms,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi khi lấy danh sách phòng: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'subdomain' => $this->Isubdomain(),
                'request' => $request->all(),
            ]);

            return response()->json([
                'message' => 'Đã xảy ra lỗi trong quá trình xử lý. Vui lòng thử lại sau.'
            ], 500);
        }
    }
}
