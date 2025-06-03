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

        $check_status = OtaSetting::whereHas('hotelFacility', function ($q) {
            $q->where('trang_thai', 1);
        })->where('status', 1)->get();
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
                        'hotelFacility.amenities' => function ($query)use ($hotel) {
                            $query->where('status', 1);
                            $query->where('unit_code',  $hotel->ma_coso);
                            $query->select('icon', 'title', 'subdomain');
                        },
                         'hotelFacility.facilities' => function ($query)use ($hotel) {
                            $query->where('status', 1);
                            $query->where('unit_code',  $hotel->ma_coso);
                            $query->select('icon', 'title', 'subdomain');
                        },
                        'hotelFacility.roomTypePrice' => function ($query) use ($hotel) {
                            $query->where('unit_code', $hotel->ma_coso);
                        }
                    ])
                        ->select('hotel_name', 'slug', 'address', 'province', 'phone', 'external_link', 'logo','main_image', 'hotel_facility_id', 'longitude', 'latitude')->first();
                    if ($hotelConfig && $hotelConfig->hotelFacility) {
                        if ($hotelConfig->logo) {
                            $hotelConfig->logo = 'https://app.fasthotel.vn/storage' . '/' . ltrim($hotelConfig->logo, '/');
                        }

                        // Thêm prefix vào gallery image URLs
                        if ($hotelConfig->hotelFacility && $hotelConfig->hotelFacility->galleryImages) {
                            foreach ($hotelConfig->hotelFacility->galleryImages as $image) {
                                if ($image->image_url) {
                                    $image->image_url = 'https://app.fasthotel.vn/storage' . '/' . ltrim($image->image_url, '/');
                                }
                            }
                        }
                        // Lấy min/max unit_price nếu có dữ liệu
                        $roomPrices = $hotelConfig->hotelFacility->roomTypePrice;
                        $maxPrice = $roomPrices->max('unit_price');
                        $minPrice = $roomPrices->min('unit_price');

                        // Gắn vào JSON trả về
                        $hotelConfig->max_price = $maxPrice;
                        $hotelConfig->min_price = $minPrice;
                        // ẩn các trường không cho hiển thị ra 
                        $hotelConfig->makeHidden(['hotel_facility_id']);
                        $hotelConfig->hotelFacility->makeHidden(['subdomain', 'ma_coso']);
                        $hotelConfig->hotelFacility->amenities->makeHidden(['subdomain']);
                          $hotelConfig->hotelFacility->facilities->makeHidden(['subdomain']);
                        $hotelConfig->hotelFacility->galleryImages->makeHidden(['hotel_facility_id']);
                        $hotelConfig->hotelFacility->makeHidden(['roomTypePrice']);
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
        $searchRoomNumber = $request->input('room_number');
        $searchRoomType = $request->input('room_type');
        $searchAmenities = $request->input('amenities');
        $searchFacilities = $request->input('facilities');

        $hotelFacility = HotelFacility::find($HotelConfiguration->hotel_facility_id);
        $otaSetting = OtaSetting::where('hotel_id', $HotelConfiguration->hotel_facility_id)->first();
        $rooms = Room::withoutTenant()->where('subdomain', $hotelFacility->subdomain)->where('unit_code', $hotelFacility->ma_coso)->active();
        $rooms->select('id', 'room_number', 'room_type_id', 'main_image', 'is_clean', 'total_adult', 'total_child', 'beds', 'description');

        if (!empty($searchRoomNumber)) {
            $rooms->where('room_number', 'like', '%' . $searchRoomNumber . '%');
        }

        // loại phòng
        if (!empty($searchRoomType)) {
            $rooms->whereHas('roomType', function ($q) use ($searchRoomType) {
                $q->where('name', 'like', '%' . $searchRoomType . '%');
            });
        }
        // tiện nghi
        if (!empty($searchAmenities)) {
            $rooms->whereHas('amenities', function ($q) use ($searchAmenities) {
                $q->where('title', 'like', '%' . $searchAmenities . '%');
            });
        }
        // cơ sở
        if (!empty($searchFacilities)) {
            $rooms->whereHas('facilities', function ($q) use ($searchFacilities) {
                $q->where('title', 'like', '%' . $searchFacilities . '%');
            });
        }
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
            'amenities',
            'facilities',
            'images'
            // 'roomBookingHistory.checkInData',
            // 'roomBookingHistory.bookingData',
        ]);

        $rooms = $rooms->get();
        return response()->json([
            'rooms' => $rooms,
            'hotel' =>  $HotelConfiguration,
        ], 200);
    }
}
