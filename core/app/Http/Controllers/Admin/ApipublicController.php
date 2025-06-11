<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelConfiguration;
use App\Models\HotelFacility;
use App\Models\OtaSetting;
use App\Models\Room;
use App\Models\RoomStatusHistory;
use App\Traits\HasTodayPrice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class ApipublicController extends Controller
{
    use HasTodayPrice;
    protected  function Isubdomain()
    {
        $host = request()->getHost();
        return explode('.', $host)[0];
    }

    public function getHotels(Request $request)
    {
        $data = [];
        $hotelName = $request->input('hotel_name');
        $province_id = $request->input('province_id');
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

                    if ($province_id) {
                        $hotelConfigQuery->where('province_code',   $province_id);
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
                        'hotelFacility.amenities' => function ($query) use ($hotel) {
                            $query->where('status', 1);
                            $query->where('unit_code',  $hotel->ma_coso);
                            $query->select('icon', 'title', 'subdomain');
                        },
                        'hotelFacility.facilities' => function ($query) use ($hotel) {
                            $query->where('status', 1);
                            $query->where('unit_code',  $hotel->ma_coso);
                            $query->select('icon', 'title', 'subdomain');
                        },
                        'hotelFacility.roomTypePrice' => function ($query) use ($hotel) {
                            $query->where('unit_code', $hotel->ma_coso);
                        }
                    ])
                        ->select('hotel_name', 'slug', 'address', 'province', 'province_code', 'phone', 'external_link', 'logo', 'main_image', 'hotel_facility_id', 'longitude', 'latitude', 'email', 'chinh_sach', 'gioi_thieu')->first();
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
        $HotelConfiguration = HotelConfiguration::where('slug', $hotel)->with([
            'hotelFacility' => function ($query) {
                $query->select('id', 'ma_coso', 'ten_coso', 'subdomain'); // cần giữ 'id' để join
            },
            'hotelFacility.galleryImages' => function ($query) {
                $query->select('hotel_facility_id', 'image_url');
            },
            'hotelFacility.amenities' => function ($query) use ($hotel) {
                $query->where('status', 1);
                $query->select('icon', 'title', 'subdomain');
            },
            'hotelFacility.facilities' => function ($query) use ($hotel) {
                $query->where('status', 1);
                $query->select('icon', 'title', 'subdomain');
            },
        ])->select('id', 'hotel_name', 'slug', 'address', 'province', 'phone', 'external_link', 'logo', 'main_image', 'hotel_facility_id', 'longitude', 'latitude', 'email', 'chinh_sach', 'gioi_thieu')->first();
        if (!$HotelConfiguration) {
            return response()->json([
                'message' => 'Khách sạn không tồn tại',
            ], 404);
        }
        $date = $request->input('date', Carbon::today()->toDateString());
        $checkInDate = $request->input('date_star', now()->toDateString());
        $checkOutDate = $request->input('date_end', now()->addDay()->toDateString());


        $searchRoomNumber = $request->input('room_number');
        $searchRoomType = $request->input('room_type');
        $searchAmenities = $request->input('amenities');
        $searchFacilities = $request->input('facilities');

        $hotelFacility = HotelFacility::find($HotelConfiguration->hotel_facility_id);
        $otaSetting = OtaSetting::where('hotel_id', $HotelConfiguration->hotel_facility_id)->first();
        $dates = $this->getDates($checkInDate, $checkOutDate);
        $pricesByRoomTypeId = $this->getPricesBySetupPricingForMultipleDatesApi($dates);
        $rooms = Room::withoutTenant()->where('subdomain', $hotelFacility->subdomain)->where('unit_code', $hotelFacility->ma_coso)->where('room_fix', 0)->active();
        $rooms->select('id', 'room_number', 'room_type_id', 'main_image', 'is_clean', 'total_adult', 'total_child', 'beds', 'description', 'area', 'direction');

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
        // cơ sở vật chất
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
            'roomBookingHistory' => function ($query) use ($date) {
                if (!empty($date)) {
                    $query->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>', Carbon::parse($date)->subDay())
                        ->select('room_id', 'status_code', 'start_date', 'end_date');
                }
            },
            'roomBookingHistory.roomStatus',
            'amenities' => function ($query) {
                $query->select('title', 'icon');
            },
            'facilities' => function ($query) {
                $query->select('title', 'icon');
            },
            'images' => function ($query) {
                $query->select('image', 'room_id');
            },
            // 'roomBookingHistory.checkInData',
            // 'roomBookingHistory.bookingData',
        ]);
        $rooms = $rooms->get();
        $allRoomStatusHistory = collect();
        $newRecords = [];
        foreach ($rooms as $room) {
            $roomStatusHistory = RoomStatusHistory::where('room_id', $room->id)
                ->with('roomStatus')
                ->get();

            $allRoomStatusHistory = $allRoomStatusHistory->merge($roomStatusHistory);
        }
        $groupedByRoom = $allRoomStatusHistory->groupBy('room_id');
        $filteredResults = collect();

        foreach ($groupedByRoom as $roomId => $records) {
            $sortedRecords = $records->sortBy('start_date')->values();
            $uniqueRecords = collect();

            foreach ($sortedRecords as $record) {
                $overlapIndex = $uniqueRecords->search(function ($item) use ($record) {
                    return ($record->start_date == $item->start_date && $record->end_date == $item->end_date) ||
                        ($record->start_date < $item->end_date && $record->end_date > $item->start_date);
                });

                if ($overlapIndex !== false) {
                    $existingRecord = $uniqueRecords[$overlapIndex];
                    if ($record->status_code > $existingRecord->status_code) {
                        $uniqueRecords[$overlapIndex] = $record;
                    }
                } else {
                    $uniqueRecords->push($record);
                }
            }

            $filteredResults = $filteredResults->merge($uniqueRecords);
        }
        $newRecords = [];
        $appliedPrice = null;
        foreach ($rooms as $room) {
            foreach ($dates as $date) {
                $status = 0;
                $check_booked = "Trống";
                $selectedStatus = null;

                $roomRecords = $filteredResults->filter(function ($item) use ($room, $date) {
                    $formattedDate = Carbon::parse($date)->format('Y-m-d');
                    $startDate = Carbon::parse($item->start_date)->format('Y-m-d');
                    $endDate = Carbon::parse($item->end_date)->format('Y-m-d');

                    $daysDifference = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));

                    if ($daysDifference == 1) {
                        return $item->room_id == $room->id && $formattedDate == $startDate;
                    } else {
                        $endDate = Carbon::parse($item->end_date)->subDay()->format('Y-m-d');
                        return $item->room_id == $room->id && $formattedDate >= $startDate && $formattedDate <= $endDate;
                    }
                });

                if ($roomRecords->isNotEmpty()) {
                    $selectedStatus = $roomRecords->sortByDesc('status_code')->first();
                }

                if ($selectedStatus !== null) {
                    if ($selectedStatus->status_code == 1) {
                        $check_booked = "Trống";
                        $status = 0;
                    } elseif ($selectedStatus->status_code == 2) {
                        $check_booked = "Đã đặt";
                        $status = 1;
                    } elseif ($selectedStatus->status_code == 3) {
                        $check_booked = "Đã nhận";
                        $status = 1;
                    }
                }


                if (isset($pricesByRoomTypeId[$date][$room->room_type_id])) {
                    $appliedPrice = $pricesByRoomTypeId[$date][$room->room_type_id]->unit_price;
                }

                $newRecords[] = [
                    "room_type_id"  => $room->room_type_id,
                    "date"          => $date,
                    "check_booked"  => $check_booked,
                    //  "status"        => $status,
                    "applied_price" => $appliedPrice,
                ];
            }
        }
        // $rooms->transform(function ($room) use ($pricesByRoomTypeId) {
        //     $room->applied_price = $pricesByRoomTypeId[$room->room_type_id] ?? null;

        //     return $room;
        // });

        $domain = 'https://app.fasthotel.vn/storage'; // hoặc gán cứng ví dụ: $domain = 'https://yourdomain.com/';
        $today = Carbon::now()->toDateString();
        $formattedRooms = $rooms->map(function ($room) use ($domain, $newRecords, $today) {
            $roomTypeId = $room->room_type_id;

            $matchedRecords = array_filter($newRecords, function ($record) use ($roomTypeId) {
                return $record['room_type_id'] == $roomTypeId;
            });
            $matchedRecords = array_values($matchedRecords);
            $cleanedRecords = array_map(function ($record) {
                unset($record['room_type_id']);
                return $record;
            }, $matchedRecords);
            $room->daily_room_rate = $cleanedRecords; // ds giá khoảng ngày đã chọn
         
            $todayRecord = collect($matchedRecords)->firstWhere('date', $today);
            $room->unit_price = $todayRecord['applied_price']; // giá ngày hôm nay
            $roomTypeName = optional($room->roomType)->name;
            $room->room_type = $roomTypeName; // tên loại phòng
            // Gán link đầy đủ cho ảnh phòng và ảnh loại phòng
            $room->main_image = $room->main_image ? $domain . '/' . ltrim($room->main_image, '/') : null;
            if (!empty($room->roomType->main_image)) {
                $room->room_type_image = $domain . '/' . ltrim($room->roomType->main_image, '/');
            }

            $room->is_clean = $room->is_clean ? 'Đã dọn' : 'Chưa dọn';

            // Bỏ trường không cần
            unset($room->room_type_id);
            unset($room->room_type_id);
            unset($room->roomType); // bỏ object roomType
            // Chuyển amenities & facilities chỉ còn title và icon
            if ($room->amenities) {
                $room->amenities = $room->amenities->map(function ($a) {
                    return [
                        'title' => $a->title,
                        'icon'  => $a->icon,
                    ];
                });
            }

            if ($room->facilities) {
                $room->facilities = $room->facilities->map(function ($f) {
                    return [
                        'title' => $f->title,
                        'icon'  => $f->icon,
                    ];
                });
            }

            // Gắn unit price và tên loại phòng



            // Gắn link ảnh thumbnails
            if (isset($room->images)) {
                $room->images->transform(function ($img) use ($domain) {
                    $img->image = $img->image ? $domain . '/' . ltrim($img->image, '/') : null;
                    return $img;
                });
            }

            // Tùy chỉnh room_booking_history thành chuỗi trạng thái
            if ($room->roomBookingHistory->isEmpty()) {
                $room->room_booking_history = 'Phòng trống';
            } else {
                $statusCode = (int) $room->roomBookingHistory->first()->status_code; // ép kiểu về int
                $room->room_booking_history = match ($statusCode) {
                    2 => 'Phòng đã đặt',
                    3 => 'Phòng đang ở',
                    default => 'Trạng thái không xác định'
                };
            }


            // Bỏ object cũ (chứa đầy đủ history)
            unset($room->roomBookingHistory);

            return $room;
        });
        if ($HotelConfiguration->main_image) {
            $HotelConfiguration->main_image = 'https://app.fasthotel.vn/storage' . '/' . ltrim($HotelConfiguration->main_image, '/');
        }

        // Thêm prefix vào gallery image URLs
        if ($HotelConfiguration->hotelFacility && $HotelConfiguration->hotelFacility->galleryImages) {
            foreach ($HotelConfiguration->hotelFacility->galleryImages as $image) {
                if ($image->image_url) {
                    $image->image_url = 'https://app.fasthotel.vn/storage' . '/' . ltrim($image->image_url, '/');
                }
            }
        }
        $HotelConfiguration->hotelFacility->makeHidden(['subdomain', 'ma_coso']);
        $HotelConfiguration->hotelFacility->amenities->makeHidden(['subdomain']);
        $HotelConfiguration->hotelFacility->facilities->makeHidden(['subdomain']);
        unset($HotelConfiguration->icon);
        unset($HotelConfiguration->hotel_facility_id);
        unset($HotelConfiguration->logo);
        return response()->json([
            'rooms' => $formattedRooms,
            'hotel' =>  $HotelConfiguration,
        ], 200);
    }
}
