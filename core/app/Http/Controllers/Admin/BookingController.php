<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookedRoom;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use App\Constants\Status;
use App\Http\Responses\ApiResponse;
use App\Models\PremiumService;
use App\Models\RoomPriceRoom;
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
            ->whereDate('booked_for', now()->toDateString())    // check hôm nay có phòng nào chưa đặt không
            ->get();

        $disabledRoomTypeIDs = RoomType::where('status', 0)->pluck('id')->toArray(); // Lấy danh sách các ID của loại phòng bị vô hiệu hóa
        $bookedRooms         = $rooms->pluck('room_id')->toArray(); // Lấy danh sách các ID của phòng đã được đặt hôm nay.
        $emptyRooms          = Room::active()->whereNotIn('id', $bookedRooms)->whereNotIn('room_type_id', $disabledRoomTypeIDs)->with('roomType:id,name', 'roomPrices')->select('id', 'room_type_id', 'room_number')->get();

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
        $pageTitle = 'Kiểm tra hôm nay';
        $bookings = $this->bookingData('todayCheckIn');
        return view('admin.booking.list', compact('pageTitle', 'bookings'));
    }

    public function todayCheckoutBooking()
    {
        $pageTitle = 'Thanh toán hôm nay';
        $bookings = $this->bookingData('todayCheckout');
        return view('admin.booking.list', compact('pageTitle', 'bookings'));
    }

    public function refundableBooking()
    {
        $pageTitle = 'Đặt chỗ có thể hoàn lại';
        $bookings = $this->bookingData('refundable');
        return view('admin.booking.list', compact('pageTitle', 'bookings'));
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

    public function bookingDetails(Request $request,  $id)
    {
        $booking = Booking::with([
            'bookedRooms',
            'activeBookedRooms:id,booking_id,room_id',
            'activeBookedRooms.room:id,room_number',
            'bookedRooms.room:id,room_type_id,room_number',
            'bookedRooms.room.roomType:id,name',
            'usedPremiumService.room',
            'usedPremiumService.premiumService',
            'payments'
        ])->findOrFail($id);
        // BookedRoom::where('booking_id', $id)->with('booking.user', 'room.roomType')->orderBy('booked_for')->get()->groupBy('booked_for');

        if ($request->is_method === 'receptionist') {
            $returnedPayments  = $booking->payments->where('type', 'RETURNED');
            $receivedPayments  = $booking->payments->where('type', 'RECEIVED');
            $total_amount      = $booking->total_amount;
            $due               = $booking->due();

            return response()->json(['status' => 'success', 'data' => $booking, 'returnedPayments' => $returnedPayments, 'receivedPayments' => $receivedPayments, 'total_amount' => $total_amount, 'due' => $due]);
        }
        $pageTitle = 'Chi tiết đặt chỗ';
        return view('admin.booking.details', compact('pageTitle', 'booking'));
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



    public function Receptionist()
    {

        $emptyMessage   = '';
        $pageTitle      =  'Lễ tân';
        $Title          =  'Tất cả các phòng';


        $rooms = BookedRoom::active()
            ->with([
                'room',
                'room.roomType',
                'booking:id,user_id,booking_number',
                'booking.user:id,firstname,lastname',
                'usedPremiumService.premiumService:id,name'
            ])
            ->whereDate('booked_for', now()->toDateString())
            ->get();
        $roomCounts = [
            'status-dirty' => 0,
            'status-incoming' => 0,
            'status-occupied' => 0,
            'status-late-checkin' => 0,
            'status-check-out' => 0,
        ];
        $disabledRoomTypeIDs = RoomType::where('status', 0)->pluck('id')->toArray();
        $bookedRooms         = $rooms->pluck('room_id')->toArray();
        $emptyRooms          = Room::active()->has('roomPricesActive')
            ->whereNotIn('id', $bookedRooms)
            ->whereNotIn('room_type_id', $disabledRoomTypeIDs) // loại trừ nhũng phòng ngưng hoạt động hoạt vô hiệu hóa
            ->with('roomType', 'roomPricesActive')
            ->select('id', 'room_type_id', 'room_number', 'is_clean')
            ->get();
        $scope = 'ALL';
        $is_method = 'Receptionist';
        $bookings = BookedRoom::active()->with('booking', 'roomType', 'room', 'room.roomPricesActive', 'usedPremiumService')->get();

        $userList = User::select('username', 'email', 'mobile', 'address')->get();

        return view('admin.booking.receptionist.list', compact('pageTitle', 'Title', 'emptyRooms', 'bookings', 'emptyMessage', 'userList', 'roomCounts'));
    }
    public function changeCleanRoom(Request $request)
    {
        try {

            $room = Room::where('room_number', $request->roomData)->firstOrFail();

            $newStatus = ($room->is_clean == Status::ROOM_CLEAN_ACTIVE) ? 0 : 1;
            $room->update(['is_clean' => $newStatus]);

            return ApiResponse::success('', 'success', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return ApiResponse::error('error', 404);
        } catch (\Exception $e) {

            return ApiResponse::error($e->getMessage(), 404);
        }
    }
    public function getPremiumServices()
    {
        $pageTitle          = 'Thêm dịch vụ cao cấp';
        $premiumServices    = PremiumService::active()->get();
        return ApiResponse::success($premiumServices, 'success', 200);
    }
}
