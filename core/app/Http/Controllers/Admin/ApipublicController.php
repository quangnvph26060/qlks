<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelConfiguration;
use App\Models\HotelFacility;
use App\Models\OtaSetting;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class ApipublicController extends Controller
{
    protected  function Isubdomain()
    {
        $host = request()->getHost();
        return explode('.', $host)[0];
    }

    public function getHotels(Request $request)
    {
        $data = [];
        $hotelName = $request->input('hotel_name');
        $address = $request->input('address');
        $amenities = $request->input('amenities');

        $check_status = OtaSetting::all();
        foreach ($check_status as $item) {
            if ($item->status == 1) {
                $hotel = HotelFacility::find($item->hotel_id);
                if ($hotel) {
                    $hotelConfigQuery  = HotelConfiguration::where('hotel_facility_id', $hotel->id)
                        ->whereHas('hotelFacility', function ($query) {
                            $query->where('trang_thai', 1);
                        });
                    // search
                    if ($hotelName) {
                        $hotelConfigQuery->where('hotel_name', 'like', '%' . $hotelName . '%');
                    }

                    if ($address) {
                        $hotelConfigQuery->where('address', 'like', '%' . $address . '%');
                    }
                    // Nếu có lọc theo tiện ích
                    if (!empty($amenities)) {
                        $hotelConfigQuery->whereHas('hotelFacility.amenities', function ($query) use ($amenities) {
                            $query->where('status', 1)
                                ->where('title', 'like', '%' . $amenities . '%'); // cho phép tìm gần đúng
                        });
                    }
                    $hotelConfig = $hotelConfigQuery->with([
                        'hotelFacility' => function ($query) {
                            $query->select('id', 'ma_coso', 'ten_coso', 'subdomain'); // cần giữ 'id' để join
                        },
                        'hotelFacility.galleryImages' => function ($query) {
                            $query->select('hotel_facility_id', 'image_url');
                        },
                        'hotelFacility.amenities' => function ($query) {
                            $query->where('status', 1);
                            $query->select('icon', 'title', 'subdomain');
                        }
                    ])
                        ->select('hotel_name', 'slug', 'address', 'phone', 'external_link', 'logo', 'hotel_facility_id')->first();
                    if ($hotelConfig && $hotelConfig->hotelFacility) {
                        $hotelConfig->hotelFacility->makeHidden(['subdomain']);
                        $hotelConfig->hotelFacility->amenities->makeHidden(['subdomain']);
                    }
                    if ($hotelConfig) {
                        $data[] = $hotelConfig;
                    }
                }
            }
        }

        return response()->json([
            'hotels' => $data,

        ], 200);
    }

    public function getRooms(Request $request, $hotel)
    {
        $HotelConfiguration = HotelConfiguration::where('slug', $hotel)->first();
        if (!$HotelConfiguration) {
            return response()->json([
                'message' => 'Khách sạn không tồn tại',
            ], 404);
        }
        $date = $request->input('date', Carbon::today()->toDateString());
        $hotelFacility = HotelFacility::find($HotelConfiguration->hotel_facility_id);
        $otaSetting = OtaSetting::where('hotel_id', $HotelConfiguration->hotel_facility_id)->first();
        $rooms = Room::withoutTenant()->where('subdomain', $hotelFacility->subdomain)->active();
        $rooms->select('id', 'room_number', 'room_type_id','main_image', 'is_clean', 'total_adult', 'total_child', 'beds', 'description');

        if (!$otaSetting->allow_all_rooms) {
            $filtered = false;
           
            // Ưu tiên lọc theo ID phòng nếu có
            $allowedRooms = $otaSetting->allowed_rooms;
            if (is_array($allowedRooms) && count($allowedRooms)) {
                $rooms->whereIn('id', $allowedRooms);
                $filtered = true;
            }
            if (!$filtered) {
              
                $allowedRoomTypes = $otaSetting->allowed_room_types;
           
                if (is_array($allowedRoomTypes) && count($allowedRoomTypes)) {
                    $rooms->whereIn('room_type_id', $allowedRoomTypes);
                }
            }
        }

        $rooms->with([
            'roomType' => function ($query) {
                $query->select('id', 'name', 'main_image', 'slug');
            },
            'roomType' => function ($query) use ($date) {
                $query->select('id', 'name', 'main_image', 'slug')
                    ->with(['roomTypePriceForDate' => function ($q) use ($date) {
                        $q->select('room_type_id', 'unit_price', 'overtime_price', 'extra_person_price')
                            ->whereDate('price_validity_period', '<=', $date)
                            ->orderByDesc('price_validity_period')
                            ->limit(1); // Chỉ lấy giá có hiệu lực gần nhất theo ngày
                    }]);
            },
            'roomBookingHistory' => function ($query) use ($date) {
                if (!empty($date)) {
                    $query->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>', Carbon::parse($date)->subDay())
                        ->select('room_id', 'status_code', 'start_date', 'end_date');
                }
            },
            'roomBookingHistory.roomStatus',
            'amenities', 'facilities', 'images'
            // 'roomBookingHistory.checkInData',
            // 'roomBookingHistory.bookingData',
        ]);

        $rooms = $rooms->get();
        return response()->json([
            'rooms' => $rooms,
        ], 200);
    }
    // public function getRooms(Request $request, $hotel)
    // {
    //     try {
    //         $otaSetting = OtaSetting::where('subdomain', $this->Isubdomain())->first();
    //         if (!$otaSetting || $otaSetting->status != 1) {
    //             return response()->json([
    //                 'message' => 'Không có quyền truy cập'
    //             ], 401);
    //         }

    //         $date = $request->input('date', Carbon::today()->toDateString());

    //         $rooms = Room::withoutTenant()->where('subdomain', $this->Isubdomain())->active();
    //         $rooms->select('id', 'room_number', 'room_type_id', 'is_clean', 'total_adult', 'total_child', 'beds', 'description');

    //         if (!$otaSetting->allow_all_rooms) {
    //             $filtered = false;

    //             // Ưu tiên lọc theo ID phòng nếu có
    //             $allowedRooms = $otaSetting->allowed_rooms;
    //             if (is_array($allowedRooms) && count($allowedRooms)) {
    //                 $rooms->whereIn('id', $allowedRooms);
    //                 $filtered = true;
    //             }

    //             if (!$filtered) {
    //                 $allowedRoomTypes = is_string($otaSetting->allowed_room_types)
    //                     ? json_decode($otaSetting->allowed_room_types, true)
    //                     : [];

    //                 if (is_array($allowedRoomTypes) && count($allowedRoomTypes)) {
    //                     $rooms->whereIn('room_type_id', $allowedRoomTypes);
    //                 }
    //             }
    //         }

    //         $rooms->with([
    //             'roomType' => function ($query) {
    //                 $query->select('id', 'name', 'main_image', 'slug');
    //             },
    //             'roomType.roomTypePrice' => function ($query) {
    //                 $query->select('room_type_id', 'unit_price', 'overtime_price', 'extra_person_price');
    //             },
    //             'roomBookingHistory' => function ($query) use ($date) {
    //                 if (!empty($date)) {
    //                     $query->whereDate('start_date', '<=', $date)
    //                         ->whereDate('end_date', '>', Carbon::parse($date)->subDay())
    //                         ->select('room_id', 'status_code', 'start_date', 'end_date');
    //                 }
    //             },
    //             'roomBookingHistory.roomStatus',
    //             // 'roomBookingHistory.checkInData',
    //             // 'roomBookingHistory.bookingData',
    //         ]);

    //         $rooms = $rooms->get();

    //         return response()->json([
    //             'rooms' => $rooms,
    //         ], 200);
    //     } catch (\Exception $e) {
    //         Log::error('Lỗi khi lấy danh sách phòng: ' . $e->getMessage(), [
    //             'trace' => $e->getTraceAsString(),
    //             'subdomain' => $this->Isubdomain(),
    //             'request' => $request->all(),
    //         ]);

    //         return response()->json([
    //             'message' => 'Đã xảy ra lỗi trong quá trình xử lý. Vui lòng thử lại sau.'
    //         ], 500);
    //     }
    // }
}
