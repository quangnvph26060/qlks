<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookedRoom;
use App\Models\CustomerSource;
use App\Models\RoomChange;
use App\Models\RoomStatusHistory;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\UsedPremiumService;
use App\Models\User;
use App\Models\UserdProductRoom;
use Illuminate\Http\Request;
use App\Constants\Status;
use App\Http\Responses\ApiResponse;
use App\Models\Admin;
use App\Models\CheckIn;
use App\Models\PremiumService;
use App\Models\Product;
use App\Models\ReceiptAndPayment;
use App\Models\RegularRoomPrice;
use App\Models\RoomBooking;
use App\Models\RoomPriceRoom;
use App\Models\RoomPricesWeekdayHour;
use App\Models\RoomTypePrice;
use App\Models\UserCleanroom;
use Carbon\Carbon;
use Hamcrest\Arrays\IsArray;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\HttpKernel\Log\Logger;

class BookingController extends Controller
{
    public function todaysBooked()
    {
        $pageTitle = request()->type == 'not_booked' ? 'Phòng có sẵn để đặt hôm nay' : 'Phòng đã đặt hôm nay';

        $rooms = BookedRoom::active()
            ->with([
                'room:id,room_number,room_type_id',
                'room.roomType:id,name',
                'booking:id,user_id,booking_number',
                'booking.user:id,firstname,lastname',
                'usedPremiumService.premiumService:id,name'
            ])
            ->whereDate('booked_for', now()->toDateString())   // Lấy các phòng có lịch đặt vào ngày hôm nay
            ->get();

        $disabledRoomTypeIDs = RoomType::where('status', 0)->pluck('id')->toArray(); // Lấy danh sách các ID của loại phòng bị vô hiệu hóa
        $bookedRooms         = $rooms->pluck('room_id')->toArray(); // Lấy danh sách các ID của phòng đã được đặt hôm nay.
        $emptyRooms          = Room::active()->whereNotIn('id', $bookedRooms)->whereNotIn('room_type_id', $disabledRoomTypeIDs)->with('roomType:id,name')->select('id', 'room_type_id', 'room_number')->get();
        return view('admin.booking.todays_booked', compact('pageTitle', 'rooms', 'emptyRooms'));
    }

    public function activeBookings()
    {
        $pageTitle = 'Đặt chỗ đang hoạt động';
        $bookings = $this->bookingData('active');
        return view('admin.booking.list', compact('pageTitle', 'bookings'));
    }

    public function checkedOutBookingList()
    {
        $pageTitle = 'Đã kiểm tra Đặt phòng';
        $bookings = $this->bookingData('checkedOut');
        return view('admin.booking.list', compact('pageTitle', 'bookings'));
    }

    public function delayedCheckout()
    {
        $pageTitle = 'Đặt phòng thanh toán bị trì hoãn';
        $bookings = $this->bookingData('delayedCheckOut');
        return view('admin.booking.list', compact('pageTitle', 'bookings'));
    }

    public function canceledBookingList()
    {
        $pageTitle = 'Đặt phòng đã hủy';
        $bookings = $this->bookingData('canceled');

        return view('admin.booking.list', compact('pageTitle', 'bookings'));
    }

    public function allBookingList()
    {
        $pageTitle = 'Tất cả phòng';
        $bookings = $this->bookingData('ALL');
        return view('admin.booking.list', compact('pageTitle', 'bookings'));
    }

    public function todayCheckInBooking()
    {
        $pageTitle = 'Danh sách nhận phòng';
        return view('admin.booking.list', compact('pageTitle'));
    }
    //123
    public function getBooking(Request $request)
    {


        $perPage = 10;
        $roomBookings = CheckIn::with('room', 'admin')
            ->where('unit_code', unitCode())
            ->when(!empty($request->data['bookingCode']), function ($query) use ($request) {
                $query->where('check_in_id', 'LIKE', '%' . $request->data['bookingCode'] . '%');
            })
            ->when(!empty($request->data['customerName']), function ($query) use ($request) {
                $query->where('customer_name', 'LIKE', '%' . $request->data['customerName'] . '%');
            })
            // ->when(!empty($request->data['roomCode']), function ($query) use ($request) {
            //     $query->where('room_code', 'LIKE', '%' . $request->data['roomCode'] . '%');
            // })
            ->when(
                !empty($request->data['roomName']),
                fn($query) => $query->whereHas(
                    'room',
                    fn($q) =>
                    $q->where('room_number', 'LIKE', '%' . $request->data['roomName'] . '%')
                )
            )
            ->orderBy('created_at', 'desc')
            ->get();
        $groupedBookings = $roomBookings->groupBy('check_in_id');
        $paginatedBookings = new LengthAwarePaginator(
            $groupedBookings->forPage($request->page, $perPage), // Dữ liệu phân trang
            $groupedBookings->count(), // Tổng số bản ghi
            $perPage, // Số bản ghi mỗi trang
            $request->page, // Trang hiện tại
            ['path' => url()->current()] // Đường dẫn phân trang
        );
        $rooms = Room::active()->select('id', 'room_number')->get();
        return response([
            'status' => 'success',
            'data' => $paginatedBookings,
            'rooms' => $rooms,
            'option_selected' => $request->data['roomCode'] ?? "",
            'pagination' => [
                'total' => $paginatedBookings->total(),
                'current_page' => $paginatedBookings->currentPage(),
                'last_page' => $paginatedBookings->lastPage(),
                'per_page' => $paginatedBookings->perPage(),
            ]
        ]);
    }

    public function todayCheckoutBooking()
    {
        $pageTitle = 'Thanh toán hôm nay';
        $bookings = $this->bookingData('todayCheckout');
        return view('admin.booking.list', compact('pageTitle', 'bookings'));
    }
    // đổi phòng
    public function refundableBooking()
    {
        return view('admin.booking.change_room');
    }
    public function getChangeRoom(Request $request)
    {
        $perPage      = 10;
        $roomBookings = CheckIn::with('room', 'admin')
            ->where('unit_code', unitCode())
            ->when(!empty($request->data['bookingCode']), function ($query) use ($request) {
                $query->where('check_in_id', 'LIKE', '%' . $request->data['bookingCode'] . '%');
            })
            ->when(!empty($request->data['customerName']), function ($query) use ($request) {
                $query->where('customer_name', 'LIKE', '%' . $request->data['customerName'] . '%');
            })
            ->when(
                !empty($request->data['roomName']),
                fn($query) => $query->whereHas(
                    'room',
                    fn($q) =>
                    $q->where('room_number', 'LIKE', '%' . $request->data['roomName'] . '%')
                )
            )
            ->where(DB::raw("DATE_SUB(checkout_date, INTERVAL 1 DAY)"), '>', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->get();
        $groupedBookings   = $roomBookings->groupBy('check_in_id');
        $paginatedBookings = new LengthAwarePaginator(
            $groupedBookings->forPage($request->page, $perPage), // Dữ liệu phân trang
            $groupedBookings->count(), // Tổng số bản ghi
            $perPage, // Số bản ghi mỗi trang
            $request->page, // Trang hiện tại
            ['path' => url()->current()] // Đường dẫn phân trang
        );
        $rooms = Room::active()->select('id', 'room_number')->get();
        return response([
            'status' => 'success',
            'data' => $paginatedBookings,
            'rooms' => $rooms,
            'option_selected' => $request->data['roomCode'] ?? "",
            'pagination' => [
                'total' => $paginatedBookings->total(),
                'current_page' => $paginatedBookings->currentPage(),
                'last_page' => $paginatedBookings->lastPage(),
                'per_page' => $paginatedBookings->perPage(),
            ]
        ]);
    }
    public function pendingCheckIn()
    {

        $pageTitle         = 'Đang chờ kiểm tra';
        $bookings   = Booking::active()->keyNotGiven()->whereDate('check_in', '<=', now())->with('user')->withCount('activeBookedRooms as total_room')->get();
        $emptyMessage = 'Không tìm thấy check-in đang chờ xử lý';
        $alertText = 'Thời gian nhận phòng cho những đặt phòng này đã qua nhưng khách vẫn chưa đến.';

        return view('admin.booking.pending_checkin_checkout', compact('pageTitle', 'bookings', 'emptyMessage', 'alertText'));
    }

    public function delayedCheckouts()
    {
        $pageTitle    = 'Thanh toán bị trì hoãn';
        $bookings     = Booking::delayedCheckout()->get();
        $emptyMessage = 'Không tìm thấy thanh toán chậm';
        $alertText = 'Thời hạn trả phòng cho những đặt phòng này đã qua nhưng khách vẫn chưa trả phòng.';
        return view('admin.booking.pending_checkin_checkout', compact('pageTitle', 'bookings', 'emptyMessage', 'alertText'));
    }

    public function upcomingCheckIn()
    {

        $pageTitle         = 'Đặt phòng sắp tới';
        $bookings          = Booking::active()->whereDate('check_in', '>', now())->whereDate('check_in', '<=', now()->addDays(gs('upcoming_checkin_days')))->with('user')->withCount('activeBookedRooms as total_room')->orderBy('check_in')->get()->groupBy('check_in');
        $emptyMessage = 'Không tìm thấy thông tin đăng ký sắp tới';

        return view('admin.booking.upcoming_checkin_checkout', compact('pageTitle', 'bookings', 'emptyMessage'));
    }

    public function upcomingCheckout()
    {
        $pageTitle       = 'Đặt phòng thanh toán sắp tới';
        $bookings        = Booking::active()->whereDate('check_out', '>', now())->whereDate('check_out', '<=', now()->addDays(gs('upcoming_checkout_days')))->with('user')->withCount('activeBookedRooms as total_room')->orderBy('check_out')->get()->groupBy('check_out');
        $emptyMessage    = 'Không tìm thấy khoản thanh toán sắp tới';

        return view('admin.booking.upcoming_checkin_checkout', compact('pageTitle', 'bookings', 'emptyMessage'));
    }
    // chi tiết đặt phòng
    public function bookingDetails(Request $request,  $id)
    {
        $pageTitle = 'Chi tiết đặt phòng';
        $booking = RoomBooking::with('room')->findOrFail($id);
        $due = $booking->due();


        return view('admin.booking.details', compact('pageTitle', 'booking', 'due'));
    }



    // chi tiết nhận phòng
    public function CheckInDetails(Request $request,  $id)
    {
        $pageTitle = 'Chi tiết nhận phòng';
        $booking = CheckIn::with('room')->findOrFail($id);
        $due = $booking->due();


        return view('admin.booking.details-check-in', compact('pageTitle', 'booking', 'due'));
    }
    public function bookingserviceproduct($id)
    {
        $service = PremiumService::get();
        $product = Product::get();
        $currentDate = Carbon::now()->format('Y-m-d');
        $used_services = UsedPremiumService::whereDate('service_date', $currentDate)
            ->where('room_id', $id)
            ->select('premium_service_id', 'qty')
            ->get();

        $used_products = UserdProductRoom::whereDate('product_date', $currentDate)
            ->where('room_id', $id)
            ->select('product_id', 'qty')
            ->get();

        return response()->json(['status' => 'success', 'service' => $service, 'product' => $product, 'used_services' => $used_services, 'used_products' => $used_products]);
    }

    public function bookedRooms(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $pageTitle = 'Phòng đã đặt';
        $bookedRooms = BookedRoom::where('booking_id', $id)->with('booking.user', 'room.roomType')->orderBy('booked_for')->get()->groupBy('booked_for');
        return view('admin.booking.booked_rooms', compact('pageTitle', 'bookedRooms', 'booking'));
    }

    protected function bookingData($scope, $is_method = null)
    {
        if ($is_method == 'Receptionist') {
            $query = Booking::active();
        } else {
            $query = Booking::query();
        }

        if ($scope != "ALL") {
            $query = $query->$scope();
        }

        $request = request();
        if ($request->search) {
            $search = $request->search;
            $query = $query->where(function ($q) use ($search) {
                $q->where('booking_number', $search)
                    ->orWhere(function ($q) use ($search) {
                        $q->whereHas('user', function ($user) use ($search) {
                            $user->where('username', 'like', "%$search%")
                                ->orWhere('email', 'like', "%$search%");
                        })
                            ->orWhere('guest_details->name', 'like', "%$search%")
                            ->orWhere('guest_details->email', 'like', "%$search%");
                    });
            });
        }

        if ($request->check_in) {
            $query = $query->whereDate('check_in', $request->check_in);
        }
        if ($request->check_out) {
            $query = $query->whereDate('check_out', $request->check_out);
        }
        return $query->with('bookedRooms.room', 'bookedRooms.roomType', 'user', 'activeBookedRooms', 'activeBookedRooms.room:id,room_number')
            ->withSum('usedPremiumService', 'total_amount')
            ->latest()
            ->orderBy('check_in', 'asc')
            ->paginate(getPaginate());
    }
    public function searchRooms(Request $request)
    {
        $startDate = Carbon::createFromFormat('m/d/Y', $request->startDate)->format('Y-m-d');
        $endDate = Carbon::createFromFormat('m/d/Y', $request->endDate)->format('Y-m-d');

        $rooms = BookedRoom::active()
            ->with([
                'room',
                'room.roomType',
                'booking:id,user_id,booking_number',
                'booking.user:id,firstname,lastname',
                'usedPremiumService.premiumService:id,name'
            ])->whereBetween('booked_for', [$startDate,  $endDate])->get();

        $disabledRoomTypeIDs = RoomType::where('status', 0)->pluck('id')->toArray();
        $bookedRooms         = $rooms->pluck('room_id')->toArray();
        $emptyRooms          = Room::active()->has('roomPricesActive')
            ->whereNotIn('id', $bookedRooms)
            ->whereNotIn('room_type_id', $disabledRoomTypeIDs) // loại trừ nhũng phòng ngưng hoạt động hoạt vô hiệu hóa
            ->with('roomType', 'roomPricesActive')
            ->select('id', 'room_type_id', 'room_number', 'is_clean')
            ->get();
    }

    public function getRoomType(Request $request)
    {
        $rooms = Room::active()->where('room_type_id', $request->id)->select('id', 'room_number')->get();
        if (!$rooms) {
            return response()->json(['status' => 'errors', 'data' => []]);
        }
        return response()->json(['status' => 'success', 'data' => $rooms]);
    }

    public function showRoom(Request $request)
    {

        $disabledRoomTypeIDs = RoomType::where('status', 0)->pluck('id')->toArray();

        if ($request->method === 'change_room') {
            $dates = $this->getDates($request->dateId, $request->dateId);
        } else {
            $dates = $this->getDates($request->checkInDate, $request->checkOutDate); // tạo 1 mảng giữa khoảng cách date
        }
        // Kiểm tra xem $roomIds có phải là mảng hay không
        $roomIds = is_array($request->roomIds) ? $request->roomIds : explode(',', $request->roomIds);

        $emptyRooms = Room::query()->active();
        if ($request->method === 'change_room') {
            $emptyRooms = $emptyRooms->whereNotIn('id', (array) $request->roomId);
        }

        $emptyRooms =  $emptyRooms->whereNotIn('room_type_id', $disabledRoomTypeIDs);
        if ($request->method != 'change_room') {
            $emptyRooms =    $emptyRooms->where(function ($query) use ($request) {
                if ($request->optionHangPhong) {
                    $query->where(function ($query) use ($request) {
                        $query->whereExists(function ($subquery) use ($request) {
                            $subquery->from('room_types')
                                ->whereRaw('rooms.room_type_id = room_types.id')
                                ->where('room_types.id', $request->optionHangPhong);
                        });
                    })->orWhere('rooms.id', $request->optionHangPhong);
                } else {
                    $query->whereExists(function ($subquery) {
                        $subquery->from('room_types');
                    });
                }
            })
                ->where(function ($query) use ($request) {
                    if (!empty($request->optionNamePhong)) {
                        $query->where('id', $request->optionNamePhong);
                    }
                });
        }

        $emptyRooms = $emptyRooms->with(['roomType', 'roomType.roomTypePrice', 'roomCheckIn', 'roomBooking', 'roomBookingChange'])
            ->select(['id', 'room_type_id', 'room_number'])
            ->get();

        $newRecords = [];

        $allRoomStatusHistory = collect();

        foreach ($emptyRooms as $room) {
            $roomStatusHistory = RoomStatusHistory::where('unit_code', unitCode())
                ->where('room_id', $room->id)
                ->with('roomStatus')
                ->get();

            $allRoomStatusHistory = $allRoomStatusHistory->merge($roomStatusHistory);
        }

        $groupedByRoom = $allRoomStatusHistory->groupBy('room_id');

        $filteredResults = collect();

        foreach ($groupedByRoom as $roomId => $records) {
            // Sắp xếp bản ghi theo start_date
            $sortedRecords = $records->sortBy('start_date')->values();
            $uniqueRecords = collect();

            foreach ($sortedRecords as $record) {
                // Kiểm tra trùng lặp với danh sách đã lọc
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
                    $lastRecord = $uniqueRecords->last();
                    // bắt đâu bằng thời gian kết thúc
                    // if ($lastRecord && $record->room_id == $lastRecord->room_id) {
                    //     $recordStartDate = Carbon::parse($record->start_date)->format('Y-m-d');
                    //     $lastEndDate = Carbon::parse($lastRecord->end_date)->format('Y-m-d');

                    //     if ($recordStartDate == $lastEndDate) {
                    //         // Xóa bản ghi trước đó để chỉ giữ bản ghi mới
                    //         $uniqueRecords->pop();
                    //         $uniqueRecords->push($record);
                    //         continue;
                    //     }
                    // }
                    $uniqueRecords->push($record);
                }
            }

            $filteredResults = $filteredResults->merge($uniqueRecords);
        }
        //  return response()->json($filteredResults);
        $newRecords = [];

        foreach ($emptyRooms as $room) {
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
                        // Nếu chỉ cách nhau 1 ngày, chỉ kiểm tra startDate
                        return $item->room_id == $room->id && $formattedDate == $startDate;
                    } else {
                        // Nếu cách nhau hơn 1 ngày, kiểm tra trong khoảng start_date -> end_date
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
                    } else {
                        $check_booked = "Đã nhận";
                        $status = 1;
                    }
                }
                $newRecords[] = [
                    "room_type_id" => $room->room_type_id,
                    "room_number"  => $room->room_number,
                    "id"           => $room->id,
                    "date"         => $date,
                    "check_booked" => $check_booked,
                    "status"       => $status,
                    "room_type"    => $room->roomType
                ];
            }
        }
        // hạng phòng
        $roomType = RoomType::active()->get();

        //  phòng
        $rooms = Room::active();
        if ($request->method === 'change_room') {
            $rooms = $rooms->where('id', $request->roomId)->first();
        } else {
            $rooms = $room->get();
        }


        $newRecordsUpdated = []; // Khởi tạo mảng mới

        foreach ($newRecords as &$item) {
            $roomIdExists = false;

            // Kiểm tra nếu checkbox đã tồn tại trước đó
            $checkboxExists = isset($item['checkbox']) && $item['checkbox'] === 'checked';

            foreach ((array) $request->roomIds as $room) {
                if (isset($room['roomId'], $room['dateId']) && $room['roomId'] == $item['id'] && $room['dateId'] == $item['date']) {
                    $roomIdExists = true;
                    break; // Nếu tìm thấy, thoát vòng lặp sớm để tối ưu
                }
            }

            // Nếu đã có checkbox hoặc tìm thấy điều kiện mới, gán lại giá trị
            if ($roomIdExists || $checkboxExists) {
                $item['checkbox'] = 'checked';
            }

            $newRecordsUpdated[] = $item;
        }


        if ($request->method === 'change_room') {
            return response()->json([
                'status' => 'success',
                // 'data'   => $emptyRooms,
                'data'               => $newRecordsUpdated,
                'room_number'        => $rooms,
                'bookingId'          => $request->bookingId,
                'roomId'             => $request->roomId,
                'id'                 => $request->Id,
                'dateBookingRoomOld' => date("d/m/Y", strtotime(now())),
            ]);
        }
        return response()->json([
            'status'        => 'success',
            'roomIds'       => $request->roomIds,
            'roomType'      => $roomType,
            'room'          => $rooms,
            'data'          => $newRecordsUpdated,
            'option_hang_phong'   => $request->optionHangPhong,
            'option_name_phong'   => $request->optionNamePhong,
            'option_status_phong' => $request->optionStatusPhong,
        ]);
    }
    public function changeRoom(Request $request)
    {
        $choice = $request->choice;
        // Log::info('change room : '.$choice);
        switch ($choice) {
            case 'room_booking':
                return $this->changeCheckIn($request);
                break;
            case 'check_in':
                return $this->changeRoomBooking($request);
                break;
            default:
                return redirect()->route('booking.list');
                break;
        }
    }
    public function changeRoomBooking(Request $request)
    {
        try {
            DB::beginTransaction();
            $roomBooking = RoomBooking::where('room_code', $request->room_old)
                // ->orWhere('room_change',$request->room_old)
                ->where('booking_id', $request->booking_id)
                ->whereDate('checkin_date', $request->date_new)
                ->with('room')->first();
            if (!$roomBooking) {
                $roomBooking = RoomBooking::where('room_change', $request->room_old)
                    ->where('booking_id', $request->booking_id)
                    ->whereDate('checkin_date', $request->date_new)
                    ->with('room')
                    ->first();
            }
            //     Log::info(  "Phòng này đã đổi {$roomBooking} ");
            if (!$roomBooking) {
                return ApiResponse::error('Không tìm thấy đơn đặt phòng', 200);
            }
            if ($roomBooking->room_change) {
                return ApiResponse::error('Phòng đã đổi một lần rồi', 200);
            }
            $isRoom = Room::where('id', $request->room_id_new)->with('roomType', 'roomType.roomTypePrice')->first();

            // $isRoomOld = Room::where('id', $roomBooking->room_change)->with('roomType', 'roomType.roomTypePrice')->first();

            // Log::info($isRoom['roomType']['roomTypePrice']['unit_price']);
            if (!$isRoom) {
                return ApiResponse::error('Không tìm thấy phòng', 200);
            }
            $isRoomChange = RoomChange::where('id_room_booking', $request->booking_id)
                ->where('old_room_code', $request->room_old)
                // ->where('new_room_code',$request->room_id_new)
                ->whereDate('checkin_date', $request->date_new)->exists();
            if ($isRoomChange) {
                return ApiResponse::error('Phòng đã được đổi trong ngày ' . Carbon::parse($request->date_new)->format('d-m-Y'), 200);
            }
            $roomChange = new RoomChange();
            $roomChange->room_change_id     = getCode('DP', 12);
            $roomChange->id_room_booking    = $roomBooking->booking_id;
            $roomChange->id_check_in        = "";
            $roomChange->old_room_code      = $roomBooking->room_change ?? $roomBooking->room_code;
            $roomChange->new_room_code      = $isRoom->id;
            $roomChange->document_date      = now();
            $roomChange->checkin_date       = $roomBooking->checkin_date;
            $roomChange->checkout_date      = $roomBooking->checkout_date;
            $roomChange->customer_code      = $roomBooking->customer_code;
            $roomChange->customer_name      = $roomBooking->customer_name;
            $roomChange->phone_number       = $roomBooking->phone_number;
            $roomChange->email              = $roomBooking->email;
            $roomChange->price_group        = $roomBooking->price_group;
            $roomChange->guest_count        = $roomBooking->guest_count;
            $roomChange->total_amount       = $isRoom['roomType']['roomTypePrice']['unit_price']; // giá tiền của phòng mới
            $roomChange->deposit_amount     = $roomBooking->deposit_amount;
            $roomChange->discount           = $roomBooking->discount;
            $roomChange->note               = "Đổi phòng từ " . ($isRoomOld->room_number ?? optional($roomBooking->room)->room_number) . " sang {$isRoom->room_number}";
            $roomChange->unit_code          = hf('ma_coso');
            $roomChange->created_by         = authAdmin()->id;
            // check nếu lại đổi phòng trùng nhau
            if ($roomBooking->room_code == $isRoom->id) {
                return ApiResponse::error('Phòng mới đổi là phòng đang ở ' . $roomBooking->room_code, 200);
            }
            $roomChange->save();

            // update phòng mới
            $roomBooking->room_change = $isRoom->id;
            $roomBooking->save();
            DB::commit();
            return ApiResponse::success('Thay đổi phòng thành công', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), 404);
        }
    }
    public function changeCheckIn(Request $request)
    {
        try {
            DB::beginTransaction();
            $roomBooking = CheckIn::where('room_code', $request->room_old)
                // ->orWhere('room_change',$request->room_old)
                ->where('check_in_id', $request->booking_id)
                ->whereDate('checkin_date', '<=', $request->date_new)
                ->whereDate('checkout_date', '>=', $request->date_new)
                ->with('room')
                ->first();
            if (!$roomBooking) {
                $roomBooking = CheckIn::where('room_change', $request->room_old)
                    ->where('check_in_id', $request->booking_id)
                    ->whereDate('checkin_date', '<=', $request->date_new)
                    ->whereDate('checkout_date', '>=', $request->date_new)
                    ->with('room')
                    ->first();
            }
            if (!$roomBooking) {
                return ApiResponse::error('Không tìm thấy đơn nhận phòng', 200);
            }
            // if ($roomBooking->room_change) {
            //     return ApiResponse::error('Phòng đã đổi một lần rồi', 200);
            // }
            $isRoom = Room::where('id', $request->room_id_new)->with('roomType', 'roomType.roomTypePrice')->first();

            if (!$isRoom) {
                return ApiResponse::error('Không tìm thấy phòng', 200);
            }
            $start_date = Carbon::parse(now());
            $end_date = Carbon::parse($roomBooking->checkout_date);

            $checkRoom = RoomStatusHistory::where('room_id', $request->room_id_new)
                ->whereIn('status_code', [2, 3])
                ->where('end_date', '!=', $end_date)
                ->where(function ($query) use ($start_date, $end_date) {
                    $query->whereBetween('start_date', [$start_date, $end_date]) // start_date nằm trong khoảng
                        ->orWhereBetween('end_date', [$start_date, $end_date]) // end_date nằm trong khoảng
                        ->orWhere(function ($q) use ($start_date, $end_date) {
                            $q->where('start_date', '<=', $start_date) // Bản ghi nằm trọn trong khoảng
                                ->where('end_date', '>=', $end_date);
                        });
                })
                ->first();

            if ($checkRoom) {
                return ApiResponse::error('Phòng '  . $isRoom->room_number .  ' đã được đặt trong ngày ' . Carbon::parse($checkRoom->start_date)->format('d-m-Y'), 200);
            }
            if ($roomBooking->room_change) {
                $roomChange = RoomChange::where('id_check_in', $request->booking_id)
                    ->where('new_room_code', $request->room_old)
                    ->first();
            } else {
                $roomChange = new RoomChange();
            }

            $roomChange->room_change_id     = $roomChange->room_change_id ?? getCode('DP', 12);
            $roomChange->id_room_booking    = $roomBooking->id_room_booking ?? "";
            $roomChange->id_check_in        = $roomBooking->check_in_id;
            $roomChange->old_room_code      = $roomBooking->room_code ?? $roomBooking->room_change;
            $roomChange->new_room_code      = $isRoom->id;
            $roomChange->document_date      = now();
            $roomChange->checkin_date       = now();
            $roomChange->checkout_date      = $roomBooking->checkout_date;
            $roomChange->customer_code      = $roomBooking->customer_code;
            $roomChange->customer_name      = $roomBooking->customer_name;
            $roomChange->phone_number       = $roomBooking->phone_number;
            $roomChange->email              = $roomBooking->email;
            $roomChange->price_group        = $roomBooking->price_group;
            $roomChange->guest_count        = $roomBooking->guest_count;
            $roomChange->total_amount       = $isRoom['roomType']['roomTypePrice']['unit_price']; // giá tiền của phòng mới
            $roomChange->deposit_amount     = $roomBooking->deposit_amount;
            $roomChange->discount           = $roomBooking->discount;
            $roomChange->note               = "Đổi phòng từ " . ($isRoomOld->room_number ?? optional($roomBooking->room)->room_number) . " sang {$isRoom->room_number}";
            $roomChange->unit_code          = hf('ma_coso');
            $roomChange->created_by         = authAdmin()->id;
            // check nếu lại đổi phòng ngược lại
            // if ($roomBooking->room_code == $isRoom->id) {
            //     return ApiResponse::error('Đổi lại chính phòng ban đầu' . $roomBooking->room_code, 200);
            // }
            $roomChange->save();

            if ($roomBooking->id_room_booking) { // đặt phòng trước 
                Log::info('đặt phòng trước ');
                saveRoomStatusHistory($isRoom->id, now(), $roomBooking->checkout_date, 3); // phòng mới 
                if ($roomBooking->room_change) {
                    Log::info('Đã đổi rồi');
                    saveRoomStatusHistory($roomBooking->room_change, $roomBooking->checkin_date, $roomBooking->checkout_date, 1); // phòng cũ 
                } else {
                    saveRoomStatusHistory($roomBooking->room_code, $roomBooking->checkin_date, $roomBooking->checkout_date, 1);
                }
            } else { // nhận phòng luôn
                Log::info('Nhận phòng luôn');
                saveRoomStatusHistory($isRoom->id, now(), $roomBooking->checkout_date, 3);
                if ($roomBooking->room_change) {
                    Log::info('Đã đổi rồi');
                    saveRoomStatusHistory($roomBooking->room_change, $roomBooking->checkin_date, $roomBooking->checkout_date, 1); // phòng cũ 
                } else {
                    saveRoomStatusHistory($roomBooking->room_code, $roomBooking->checkin_date, $roomBooking->checkout_date, 1); // phòng cũ 
                }
            }
            $roomBooking->checkin_date = now();
            $roomBooking->room_change = $isRoom->id;
            $roomBooking->save();

            DB::commit();
            return ApiResponse::success('Thay đổi phòng thành công', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), 404);
        }
    }
    public function getDates($startDate, $endDate)
    {
        $dates = [];
        $currentDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->startOfDay();

        // Luôn thêm ngày check_in vào mảng, dù chỉ có 1 ngày
        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->toDateString();
            $currentDate->addDay();
        }

        return $dates;
    }


    public function getDatesInRange($checkinDate, $checkoutDate)
    {
        $dates = [];
        $currentDate = Carbon::parse($checkinDate)->startOfDay();
        $endDate = Carbon::parse($checkoutDate)->startOfDay();

        while ($currentDate->lte($endDate)) {
            $dates[] = $currentDate->toDateString();
            $currentDate->addDay();
        }

        return $dates;
    }

    public function checkRoomBooking(Request $request)
    {

        $roomData = json_decode($request->data, true);
        $result = [];
        $lastTimePerRoom = [];
        $totalPrice = 0;
        if (isset($request->method)) {
            $room_type = RoomType::find($roomData['room_type']);

            $room = Room::active()->with('roomType', 'roomType.roomTypePrice', 'roomType.roomTypePrice.setupPricing')
                ->where('id', $roomData['room'])->first();

            $roomBooking = RoomBooking::where('room_code', $roomData['room'])
                ->whereDate('checkin_date', $roomData['date'])
                ->whereNull('room_change')
                ->where('status', 0)
                ->first();
            $checkIn = CheckIn::where('room_code', $roomData['room'])
                ->whereDate('checkin_date', $roomData['date'])
                ->whereNull('room_change')
                ->first();
            $customerSourse = CustomerSource::where('unit_code', unitCode())->get();
            $admin = Admin::where('unit_code', unitCode())->where('role_id', '!=', 0)->get();

            $roomId = $roomData['room'];
            if (!isset($lastTimePerRoom[$roomId])) {
                // Nếu là lần đầu gặp phòng này, gán giờ bắt đầu là 11:00
                $checkinTime = Carbon::now()->setSeconds(0);
            } else {
                $checkinTime = $lastTimePerRoom[$roomId]->copy()->addMinutes(1);
            }
            $lastTimePerRoom[$roomId] = $checkinTime;
            $fullCheckinDate = $checkinTime->format('H:i:s');

            if ($roomBooking || $checkIn || !$room_type || !$room) {
                $flag = 'success';
                $result[] = [
                    'room_type'              => $room_type,
                    'room'                   => $room,
                    'date'                   => $roomData['date'],
                    'admin'                  => $admin,
                    'customerSourse'         => $customerSourse,   
                    'checkin_datetime'       => $fullCheckinDate
                ];
            } else {
                $flag = 'success';
                $result[] = [
                    'room_type'              => $room_type,
                    'room'                   => $room,
                    'date'                   => $roomData['date'],
                    'admin'                  => $admin,
                    'customerSourse'         => $customerSourse,   
                    'checkin_datetime'       => $fullCheckinDate
                ];
            }
        } else {
         
            foreach ($roomData as $data) {
                $room_type = RoomType::find($data['room_type']);

                $room = Room::active()->with('roomType', 'roomType.roomTypePrice', 'roomType.roomTypePrice.setupPricing')
                    ->where('id', $data['room'])->first();

                $roomBooking = RoomBooking::where('room_code', $data['room'])
                    ->whereDate('checkin_date', $data['date'])
                    ->whereNull('room_change')
                    ->where('status', 0)
                    ->first();
                $checkIn = CheckIn::where('room_code', $data['room'])
                    ->whereDate('checkin_date', $data['date'])
                    ->whereNull('room_change')
                    ->first();

                $roomId = $data['room'];
                if (!isset($lastTimePerRoom[$roomId])) {
                    // Nếu là lần đầu gặp phòng này, gán giờ bắt đầu là 11:00
                    $checkinTime = Carbon::now()->setSeconds(0);
                } else {
                    $checkinTime = $lastTimePerRoom[$roomId]->copy()->addMinutes(1);
                }
                $lastTimePerRoom[$roomId] = $checkinTime;
                $fullCheckinDate = $checkinTime->format('H:i:s');

                if ($roomBooking || $checkIn || !$room_type || !$room) {
                    $flag = 'success';
                    $result[] = [
                        'room_type' => $room_type,
                        'room'      => $room,
                        'date'      => $data['date'],
                        'checkin_datetime' => $fullCheckinDate
                    ];
                } else {
                    $flag = 'success';
                    $result[] = [
                        'room_type' => $room_type,
                        'room'      => $room,
                        'date'      => $data['date'],
                        'checkin_datetime' => $fullCheckinDate
                    ];
                }
            }
        }

        $title = '';
        if (isset($roomData['method'])) {
            $title = 'Nhận phòng';
        }
        return response()->json(['data' => $result, 'status' => $flag, 'title' => $title]);
    }


    public function Receptionist(Request $request)
    {
        $emptyMessage   = '';
        $pageTitle      =  'Lễ tân';
        $roomTypes      = RoomType::where('unit_code', unitCode())->where('status', 1)->get();
        return view('admin.booking.receptionist.index', compact('roomTypes'));
    }
    public function roomBoookingHistory(Request $request)
    {

        $date        = request('date') ?? data_get($request->data, 'date'); // Lấy ngày từ request
        $method      = data_get($request->data, 'searchType');
        $value       = data_get($request->data, 'searchValue');
        $room_type   = data_get($request->data, 'room_type');
        $room_clean  = data_get($request->data, 'room_clean');
        // $room_status = data_get($request->data, 'room_status');
        //Log::info($date);
        $rooms = Room::query();

        $rooms->when(!empty($room_type), function ($query) use ($room_type) {
            $query->whereIn('room_type_id', $room_type);
        });

        $rooms->when(!empty($room_clean), function ($query) use ($room_clean) {
            $query->whereIn('is_clean', $room_clean);
        });

        if ($method === 'room' && !empty($value)) {
            $rooms = $rooms->where('room_number', $value);
        }
        // Nếu tìm kiếm theo khách hàng
        if ($method === 'customer' && !empty($value)) {
            $rooms->whereHas('roomBookingHistory.checkInData', function ($query) use ($value) {
                $query->where('customer_name', 'LIKE', "%$value%");
            })->orWhereHas('roomBookingHistory.bookingData', function ($query) use ($value) {
                $query->where('customer_name', 'LIKE', "%$value%");
            });
        }
        if ($method === 'booking' && !empty($value)) {
            $rooms->whereHas('roomBookingHistory.checkInData', function ($query) use ($value) {
                $query->where('id_room_booking', 'LIKE', "%$value%");
            })->orWhereHas('roomBookingHistory.bookingData', function ($query) use ($value) {
                $query->where('booking_id', 'LIKE', "%$value%");
            });
        }
        $rooms->with([
            'roomType',
            'roomType.roomTypePrice',
            'roomBookingHistory' => function ($query) use ($date) {
                if (!empty($date)) {
                    $query->whereDate('start_date', '<=', $date)
                        ->whereDate('end_date', '>', Carbon::parse($date)->subDay());
                }
                // if (!empty($room_status)) {
                //     $query->whereIn('status_code', $room_status);
                // }
            },
            'roomBookingHistory.roomStatus',
            'roomBookingHistory.checkInData',
            'roomBookingHistory.bookingData',
        ]);
        $rooms = $rooms->get();

        if (request('method') === 'list-room-booking') {
            $check_ins = RoomBooking::query()
                ->where('unit_code', unitCode())
                ->whereDate('checkin_date', '<=', $date)
                ->whereDate('checkout_date', '>', Carbon::parse($date)->subDay())
                ->with('room')
                ->get()
                ->groupBy('booking_id');
            return response()->json(['data' => $check_ins, 'status' => 'success']);
        }
        return response()->json(['data' => $rooms, 'status' => 'success']);
    }

    public function paymentRoom(Request $request)
    {
        $roomJson = $request->room[0]; // đây là chuỗi JSON
        $roomData = json_decode($roomJson, true); // chuyển thành mảng PHP
         
        $totalService = str_replace('.', '', $request->total_service);
        $totalService = (int)$totalService;
        $roomId = $roomData['room']; // lấy room
        $receipt  = ReceiptAndPayment::where('checkin_id', $request->id_room_booking)->where('room_code',$roomId)->first();
        // $check_ins = CheckIn::where('check_in_id', $request->id_room_booking)->get();
        $checkIns = CheckIn::where('check_in_id', $request->id_room_booking)->where('room_code',$roomId)->get();
        $amount = str_replace('.', '', $request->input_pttt);
        $amount = (int)$amount;
        $deposit = str_replace('.', '', $request->deposit);
        $deposit = (int)$deposit;
        if($amount <= 0) {
            return response()->json([
                'status'=> 'error',
                'msg'   => 'Vui lòng nhập số tiền thanh toán',
            ]);
        }
        if ($receipt) {
            $receipt->update([
                'total_payment'   =>  $receipt->total_payment + $amount,       // hoặc giá trị bạn muốn gán
                'payment_method'  => $request->payment_pttt,      // hoặc giá trị bạn muốn gán
                'service_fee'     => $totalService,
                'deposit_amount'  => $deposit,              // đặt cọc
            ]);

            $due = $receipt->due;
            Log::info( $due );
            if ($due == 0 && $checkIns->count()) {

                foreach ($checkIns as $check_in) {
                    if ($check_in->room_change != null) {
                        saveRoomStatusHistory($check_in->room_change, $check_in->checkin_date, $check_in->checkout_date, 1);
                    } else {
                        saveRoomStatusHistory($check_in->room_code, $check_in->checkin_date, $check_in->checkout_date, 1);
                    }
                }
                return response()->json(['status' => 'success', 'success' => 'Trả phòng thành công']);
            }
            return response()->json(['status' => 'success', 'success' => 'Thanh toán thành công']);
        } else {
            // Lấy tất cả các bản ghi có cùng check_in_id
            // Tính tổng total_amount, deposit_amount và discount_amount
            $totalAmount      = $checkIns->sum('total_amount');
            $totalDeposit     = $checkIns->sum('deposit_amount');
            $totalDiscount    = $checkIns->sum('discount_amount');
            // Tạo phiếu thu chi
            $receipt = ReceiptAndPayment::create([
                'booking_id'       => "",
                'checkin_id'       => $request->id_room_booking, // hoặc chọn 1 ID cụ thể nếu cần
                'room_price'       => $totalAmount,        // tổng tiền phòng
                'room_code'        => $roomId,
                'deposit_amount'   => $totalDeposit,       // tổng đặt cọc
                'discount_amount'  => $totalDiscount,      // tổng giảm giá
                'total_payment'    => $amount,             // tổng tiền cần thanh toán
                'payment_method'   => $request->payment_pttt,
                'service_fee'      => $totalService,
                'deposit_amount'   => $deposit, 
                'created_date'     => now(),
                'unit_code'        => hf('ma_coso')
            ]);
            $due = $receipt->due;
            Log::info($receipt);
            Log::info($checkIns);
            if ($due == 0 && $checkIns) {
                foreach ($checkIns as $check_in) {
                    if ($check_in->room_change != null) {
                        saveRoomStatusHistory($check_in->room_change, $check_in->checkin_date, $check_in->checkout_date, 1);
                    } else {
                        saveRoomStatusHistory($check_in->room_code, $check_in->checkin_date, $check_in->checkout_date, 1);
                    }
                }
                return response()->json(['status' => 'success', 'success' => 'Trả phòng thành công']);
            }
            return response()->json(['status' => 'success', 'success' => 'Thanh toán thành công']);
        }
    }
    public function changeCleanRoom(Request $request)
    {
        try {
            $room = Room::where('id', $request->id)->firstOrFail();


            $room->update(['is_clean' => $room->is_clean == Status::ROOM_CLEAN_ACTIVE ? 0 : 1]);

            $this->logCleanRoomAction($room->id, authAdmin()->id);
            if ($room->is_clean === 1) {
                $msg = 'Phòng ' . $room->room_number . ' đã được làm sạch';
            } else {
                $msg = 'Phòng ' . $room->room_number .  ' đã chuyển sang Chưa dọn';
            }

            return response()->json(['status' => 'success', 'success' => $msg]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return ApiResponse::error('error', 404);
        } catch (\Exception $e) {

            return ApiResponse::error($e->getMessage(), 404);
        }
    }
    private function logCleanRoomAction(int $roomId, int $userId): void
    {
        try {
            UserCleanroom::create([
                'room_id' => $roomId,
                'admin_id' => $userId,
                'clean_date' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error logging clean room action', ['message' => $e->getMessage()]);
            throw $e; // Re-throw để xử lý lỗi ở cấp cao hơn
        }
    }

    public function listUserCleanRoom(Request $request)
    {
        $pageTitle = 'Danh sách dọn phòng';
        $query = UserCleanroom::with('room', 'admin');
        // Xử lý tìm kiếm theo clean_date nếu có keyword được gửi từ form
        if ($request->has('keyword')) {
            $keyword = $request->keyword;
            $query->where('clean_date', 'like', "%$keyword%");
        }

        if (authCleanRoom()) {
            $query->where('admin_id', authAdmin()->id);
        }
        $userCleanRoom = $query->paginate(10);
        return view('admin.booking.cleanroom', compact('pageTitle', 'userCleanRoom'));
    }

    public function getPremiumServices()
    {
        $premiumServices    = PremiumService::active()->get();
        return ApiResponse::success($premiumServices, 'success', 200);
    }

    public function getProduct()
    {
        $product    = Product::Featured()->get();
        return ApiResponse::success($product, 'success', 200);
    }

    public function writeCccd(Request $request)
    {


        $fileName = $request->file('image')->getPathname();

        $response = writeCccd($fileName);

        if (isset($response['error'])) {
            return response()->json(['error' => $response['error']], 500);
        } else {
            return response()->json($response, 200);
        }
    }
    public function delCleanRoom($id)
    {

        if (!authCleanRoom()) {
            if (!$id) {
                return response()->json(['status' => 'error', 'message' => 'ID không hợp lệ']);
            }
            $del = UserCleanroom::find($id);

            if (!$del) {
                return response()->json(['status' => 'error', 'message' => 'Dữ liệu không tồn tại']);
            }
            $del->delete();

            return response()->json(['status' => 'success', 'message' => 'Xóa thành công']);
        }
    }

    public function listRoomBooking(Request $request)
    {
        $booking = Booking::where('booking_number', $request->booking_id)->first();
        if (!$booking) {
            return response()->json(['status' => 'error', 'message' => 'Booking number không tồn tại']);
        }
        $room = Room::active()
            ->with(['roomType', 'booked' => function ($query) use ($booking) {
                $query->where('booking_id', $booking->id);
            }])
            ->whereHas('booked', function ($query) use ($booking) {
                $query->where('booking_id', $booking->id)->where('key_status', Status::KEY_NOT_GIVEN); // 0
            })
            ->get();
        return response()->json(['status' => 'success', 'data' => $room]);
    }


    public function listRoomBooked(Request $request)
    {
        $booking = Booking::where('booking_number', $request->booking_id)->first();
        if (!$booking) {
            return response()->json(['status' => 'error', 'message' => 'Booking number không tồn tại']);
        }

        $room = Room::active()
            ->with([
                'roomType',

                'checkins' => function ($query) use ($booking) {
                    $query->where('booking_id', $booking->id);  // Điều kiện cho checkins nếu cần
                }
            ])

            ->whereHas('checkins', function ($query) use ($booking) {
                $query->where('booking_id', $booking->id)->where('key_status', Status::KEY_GIVEN);  // Điều kiện cho checkins nếu cần
            })
            ->get();

        return response()->json(['status' => 'success', 'data' => $room, 'data1' => $booking->id, 'booking' => $booking]);
    }


    public function getRoomCheckIn(Request $request)
    {
        $getRoomCheckIn = $request->selectedRooms;

        $roomCheckIn    = BookedRoom::whereIn('id', $getRoomCheckIn)->select('id', 'booking_id', 'room_id', 'room_type_id', 'fare', 'check_in_at', 'option_room')->get();
        $arrCheckIn     = $roomCheckIn->pluck('id');
        $checkInDate       = $roomCheckIn[0]['check_in_at'];
        if ($checkInDate  !== null && $roomCheckIn[0]['option_room'] === 'gio') {

            $currentDate     = now();
            $checkInDate     = Carbon::parse($checkInDate);

            $timeDiffInHours = abs($currentDate->floatDiffInHours($checkInDate));
            $periodOfTime    = ceil($timeDiffInHours); // khoảng thời gian;

        }

        $totalFare      = $roomCheckIn->sum('fare');
        $groupedData    = $roomCheckIn->groupBy('booking_id')->map(function ($items, $bookingId) {
            return [
                'booking_id' => $bookingId,
                'room_ids' => $items->pluck('room_id')->toArray()
            ];
        });

        // Kết quả là một Collection hoặc bạn có thể chuyển đổi thành mảng
        $result = $groupedData->values()->toArray();


        $booking = Booking::find($result[0]['booking_id']);
        $due = $booking->due();

        $rooms  = Room::whereIn('id', $result[0]['room_ids'])->select('room_number')->get();
        $roomNumbers = $rooms->pluck('room_number')->implode(', ');
        if ($booking['user_id']) {
            $user_booking = User::where('id', $booking['user_id'])
                ->select('username', 'mobile')
                ->first();
        }

        return response()->json([
            'status'      => 'success',
            'booking'     => $booking,
            'users'       => $user_booking,
            'rooms'       => $rooms,
            'totalFare'   => $totalFare,
            'roomNumbers' => $roomNumbers,
            'roomCheckIn' => $roomCheckIn,
            'due'         => $due,
            'ArrCheckIn'  => $arrCheckIn,
        ]);
    }
    // xóa đặt phòng

    public function deleteRoomBooking($id)
    {

        $deletedRows = RoomBooking::where('booking_id', $id)->get();
        if ($deletedRows->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy phòng.']);
        }
        foreach ($deletedRows as $roomBooking) {
            if (!empty($roomBooking->room_change)) {
                return response()->json(['status' => 'error', 'message' => 'Phòng đã có thay đổi, không thể xoá.']);
            }
            saveRoomStatusHistory($roomBooking->room_code, $roomBooking->checkin_date, $roomBooking->checkin_date, 1);
            $roomBooking->delete();
        }
        return response()->json(['status' => 'success', 'message' => 'Xoá thành công.']);
    }

    public function checkRoomBookingdel($id)
    {
        $is_check = CheckIn::where('id_room_booking', $id)->first();
        if (!$is_check) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Mã đặt phòng này không xoá được']);
        }
    }
    // xoá nhận phòng
    public function checkCheckIndel($id)
    {
        $is_check = RoomChange::where('id_check_in', $id)->first();
        if (!$is_check) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Mã nhận phòng này không xoá được']);
        }
    }
    public function deleteCheckIn($id)
    {

        $deletedRows = CheckIn::where('check_in_id', $id)->get();
        if ($deletedRows->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy phòng.']);
        }
        foreach ($deletedRows as $roomBooking) {
            if (!empty($roomBooking->room_change)) {
                return response()->json(['status' => 'error', 'message' => 'Phòng đã có thay đổi, không thể xoá.']);
            }
            $daysDifference = floor(Carbon::parse($roomBooking->checkin_date)->floatDiffInDays(Carbon::parse($roomBooking->checkout_date)));

            if ($daysDifference == 1) {
                saveRoomStatusHistory($roomBooking->room_code, $roomBooking->checkin_date, $roomBooking->checkin_date, 1);
            } else {
                saveRoomStatusHistory($roomBooking->room_code, $roomBooking->checkin_date, $roomBooking->checkout_date, 1);
            }
            $roomBooking->delete();
        }
        return response()->json(['status' => 'success', 'message' => 'Xoá thành công.']);
    }

    // THANH TOÁN   
    public function paymentList(Request $request)
    {
        $perPage = 10;
        $payments = ReceiptAndPayment::where('unit_code', unitCode())
            ->when(!empty($request->data['bookingCode']), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('checkin_id', 'LIKE', '%' . $request->data['bookingCode'] . '%')
                        ->orWhere('booking_id', 'LIKE', '%' . $request->data['bookingCode'] . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $paginatedBookings = new LengthAwarePaginator(
            $payments->forPage($request->page, $perPage), // Dữ liệu phân trang
            $payments->count(), // Tổng số bản ghi
            $perPage, // Số bản ghi mỗi trang
            $request->page, // Trang hiện tại
            ['path' => url()->current()] // Đường dẫn phân trang
        );
        $rooms = Room::active()->select('id', 'room_number')->get();
        return response([
            'status' => 'success',
            'data' => $paginatedBookings,
            'rooms' => $rooms,
            'option_selected' => $request->data['roomCode'] ?? "",
            'pagination' => [
                'total' => $paginatedBookings->total(),
                'current_page' => $paginatedBookings->currentPage(),
                'last_page' => $paginatedBookings->lastPage(),
                'per_page' => $paginatedBookings->perPage(),
            ]
        ]);
    }
    public function paymentView()
    {
        $pageTitle = ' Danh sách thanh toán';
        return view('admin.booking.payment.index', compact('pageTitle'));
    }
}
