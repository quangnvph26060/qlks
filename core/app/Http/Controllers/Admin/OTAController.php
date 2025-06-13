<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HotelFacility;
use App\Models\Ota;
use App\Models\OtaSetting;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OTAController extends Controller
{
    public function index()
    {
        $hotels    = HotelFacility::where('subdomain', subdomain())->where('trang_thai', 1)->first();

        $room_type = RoomType::all();

        $rooms = RoomType::whereHas('rooms')->with('rooms')->get();
        $otas = Ota::all();
        $ota = OtaSetting::where('subdomain', subdomain())->where('hotel_id', $hotels->id)->first();
        $data = OtaSetting::where('subdomain', subdomain())->with('hotelFacility','ota')->where('hotel_id', $hotels->id)->get();
        return view('admin.hotel.ota.index', compact('hotels', 'room_type', 'rooms', 'ota', 'otas','data'));
    }
    public function save(Request $request)
{
    $request->validate([
        'hotel_id' => 'required|exists:hotel_facilities,id',
        'ota_case' => 'required|in:1,2,3',
        'discount_code' => 'required|numeric|min:0|max:100',
    ], [
        'hotel_id.required' => 'Vui lòng chọn khách sạn.',
        'hotel_id.exists' => 'Khách sạn đã chọn không tồn tại.',

        'ota_case.required' => 'Vui lòng chọn trường hợp OTA.',
        'ota_case.in' => 'Trường hợp OTA không hợp lệ.',

        'discount_code.required' => 'Vui lòng nhập mã giảm giá.',
        'discount_code.numeric' => 'Mã giảm giá phải là số.',
        'discount_code.min' => 'Mã giảm giá không được nhỏ hơn :min%.',
        'discount_code.max' => 'Mã giảm giá không được lớn hơn :max%.',
    ]);

    try {
        $hotelId = $request->input('hotel_id');
        $case = $request->input('ota_case');
        $ota_id = $request->input('ota_id');
        $discount_code = $request->input('discount_code');
        $status = $request->input('status') ?? 0;

        $data = [
            'ota_id'             => $ota_id,
            'discount_code'      => $discount_code,
            'hotel_id'           => $hotelId,
            'allow_all_rooms'    => $case == 1 ? 1 : 0,
            'allowed_room_types' => $case == 2 ? $request->input('room_types', []) : [],
            'allowed_rooms'      => $case == 3 ? $request->input('rooms', []) : [],
            'status'             => $status,
            'subdomain'          => subdomain(),
        ];

        // Nếu có ID => cập nhật
        if ($request->filled('id')) {
            $otaSetting = OtaSetting::find($request->id);
            if (!$otaSetting) {
                $notify[] = ['error', 'Không tìm thấy cấu hình OTA để cập nhật.'];
                return back()->withNotify($notify);
            }

            $otaSetting->update($data);
            $notify[] = ['success', 'Cập nhật thành công'];
        } else {
            // Kiểm tra trùng khi tạo mới
            $exists = OtaSetting::where('hotel_id', $hotelId)
                ->where('ota_id', $ota_id)
                ->exists();

            if ($exists) {
                $notify[] = ['error', 'Cài đặt OTA cho cơ sở này đã tồn tại.'];
                return back()->withNotify($notify);
            }

            OtaSetting::create($data);
            $notify[] = ['success', 'Thêm mới thành công'];
        }

        return back()->withNotify($notify);
    } catch (\Exception $e) {
        Log::error('Lỗi khi lưu OtaSetting: ' . $e->getMessage());

        $notify[] = ['error', 'Có lỗi xảy ra, vui lòng thử lại sau'];
        return back()->withNotify($notify);
    }
}

}
