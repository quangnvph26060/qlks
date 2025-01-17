<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookedRoom;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\UsedPremiumService;
use App\Models\User;
use App\Models\UserdProductRoom;
use Illuminate\Http\Request;
use App\Constants\Status;
use App\Http\Responses\ApiResponse;
use App\Models\CheckIn;
use App\Models\PremiumService;
use App\Models\Product;
use App\Models\RegularRoomPrice;
use App\Models\RoomBooking;
use App\Models\RoomPriceRoom;
use App\Models\RoomPricesWeekdayHour;
use App\Models\RoomTypePrice;
use App\Models\UserCleanroom;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
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
    public function getBooking(Request $request)
    {
        $perPage = 10;
        $roomBookings = CheckIn::with('room', 'admin')->orderBy('created_at', 'asc')->paginate($perPage);
        return response([
            'status' => 'success',
            'data' => $roomBookings->items(),
            'pagination' => [
                'total' => $roomBookings->total(),
                'current_page' => $roomBookings->currentPage(),
                'last_page' => $roomBookings->lastPage(),
                'per_page' => $roomBookings->perPage(),
            ]
        ]);
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
    // chi tiết đặt phòng
    public function bookingDetails(Request $request,  $id)
    {
        $pageTitle = 'Chi tiết đặt phòng';
        $booking = RoomBooking::with('room')->findOrFail($id);
        $due = $booking->due();
    
       
        return view('admin.booking.details', compact('pageTitle','booking', 'due'));
    }
    public function CheckInDetails(Request $request,  $id)
    {
        $pageTitle = 'Chi tiết nhận phòng';
        $booking = CheckIn::with('room')->findOrFail($id);
        $due = $booking->due();
    
       
        return view('admin.booking.details-check-in', compact('pageTitle','booking', 'due'));
    }
    public function bookingserviceproduct($id){
        Log::info($id);
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

        return response()->json(['status' => 'success','service' => $service, 'product' => $product, 'used_services' => $used_services, 'used_products' => $used_products]);
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
//123123
    public function showRoom(Request $request) {
        $disabledRoomTypeIDs = RoomType::where('status', 0)->pluck('id')->toArray();
    
        // Kiểm tra xem $roomIds có phải là mảng hay không
        $roomIds = is_array($request->roomIds) ? $request->roomIds : explode(',', $request->roomIds);
        
        $emptyRooms = Room::active()
            ->whereNotIn('id', $roomIds)
            ->whereNotIn('room_type_id', $disabledRoomTypeIDs)
            ->with(['roomType', 'booked', 'booked.booking', 'roomType.roomTypePrice'])
            ->select(['id', 'room_type_id', 'room_number', 'is_clean'])->get();
    
        return response()->json([
            'status' => 'success',
            'data'   => $emptyRooms,
        ]);
    }
    
    public function checkRoomBooking(Request $request)
    {

        // "room_id" => "35"
        // "room_type_id" => "4"

        $room_type = RoomType::find($request->room_type_id);
        return response()->json([
            'status'    => 'success',
            'room_type' => $room_type,
            'room'      =>  Room::active()->where('id', $request->room_id)->first(),
        ]);
    }


    public function Receptionist(Request $request)
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
            ]);

        $startDate = $request->startDate ? Carbon::createFromFormat('m/d/Y', $request->startDate)->format('Y-m-d') : null;
        $endDate = $request->endDate ? Carbon::createFromFormat('m/d/Y', $request->endDate)->format('Y-m-d') : null;

        $rooms->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('booked_for', [$startDate, $endDate]);
        }, function ($query) {
            $query->whereDate('booked_for', now()->toDateString());
        })
            ->when(!empty($request->roomType), function ($query) use ($request) {
                $query->where('room_type_id', 'like', '%' . $request->roomType . '%');
            });

        $rooms = $rooms->get();


        $disabledRoomTypeIDs = RoomType::where('status', 0)->pluck('id')->toArray();


        $bookedRooms         = $rooms->pluck('room_id')->toArray();
        $idRoomActive        = RegularRoomPrice::pluck('room_price_id');
        $emptyRooms          = Room::active()
            //  ->whereNotIn('id', $bookedRooms)
            ->whereNotIn('room_type_id', $disabledRoomTypeIDs)
            // ->whereIn('id', $idRoomActive)
            
            // 'booked' => function($query){
            //                 $query->where('status',1)->whereDate('booked_for', '<=', now())->limit(1);
            //             }

            ->with(['roomType','roomType.roomTypePrice','roomCheckIn','roomBooking'])
            ->select(['id', 'room_type_id', 'room_number', 'is_clean'])
            // ->when(!empty($request->roomType), function ($query) use ($request) {
            //     $query->where('room_type_id', 'like', '%' . $request->roomType . '%');
            // })
            ->get();
        $scope = 'ALL';
       // dd($emptyRooms);
        // \Log::info($emptyRooms);
        $is_method = 'Receptionist';

        $bookings = BookedRoom::active()
            ->with([
                'booking',
                'roomType',
                'room',
                'room.roomPricesActive',
                'usedPremiumService'
            ])
            ->whereHas('booking', function ($query) use ($request) {
                if (!empty($request->codeRoom)) {
                    $query->where('booking_number', 'like', '%' . $request->codeRoom . '%');
                }

                if (!empty($request->customer)) {
                    $user = User::where('username', 'like', '%' . $request->customer . '%')->first();
                    if ($user) {
                        $query->where('user_id', $user->id);
                    } else {
                        $query->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(guest_details, "$.name")) LIKE ?', ['%' . $request->customer . '%']);
                    }
                }
            })
            ->when(!empty($request->roomType), function ($query) use ($request) {
                $query->where('room_type_id', 'like', '%' . $request->roomType . '%');
            })
            ->get();

        if (!empty($request->codeRoom) || !empty($request->customer)) {
            $emptyRooms = [];
        }
        // dd($bookings);
        $userList = User::select('username', 'email', 'mobile', 'address')->get();
        $is_result = false;
        if ($request->ajax()) {
            $is_result = true;
            return view('admin.booking.partials.empty_rooms', ['dataRooms' => $emptyRooms, 'bookings' => $bookings, 'is_result' => $is_result])->render();
        }
        $roomType = RoomType::active()->select('id', 'name')->get();
        return view('admin.booking.receptionist.list', compact('pageTitle', 'Title', 'emptyRooms', 'bookings', 'emptyMessage', 'userList', 'roomType', 'is_result'));
    }

    public function changeCleanRoom(Request $request)
    {
        try {

            $room = Room::where('room_number', $request->roomData)->firstOrFail();

            $room->update(['is_clean' => $room->is_clean == Status::ROOM_CLEAN_ACTIVE ? 0 : 1]);

            $this->logCleanRoomAction($room->id, authAdmin()->id);
            return ApiResponse::success('', 'success', 200);
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

    public function listRoomBooking(Request $request){
        $booking = Booking::where('booking_number', $request->booking_id)->first();
        if(!$booking){
            return response()->json(['status' => 'error', 'message' => 'Booking number không tồn tại']);
        }
        $room = Room::active()
        ->with(['roomType','booked' => function ($query) use ($booking) {
            $query->where('booking_id', $booking->id);
        }])
        ->whereHas('booked', function ($query) use ($booking) {
            $query->where('booking_id', $booking->id)->where('key_status', Status::KEY_NOT_GIVEN); // 0
        })
        ->get(); 
        return response()->json(['status' => 'success', 'data'=>$room]);
    }


    public function listRoomBooked(Request $request){
        $booking = Booking::where('booking_number', $request->booking_id)->first();
        if(!$booking){
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

        return response()->json(['status' => 'success', 'data'=> $room, 'data1'=> $booking->id, 'booking' => $booking]);
    }


    public function getRoomCheckIn(Request $request){
        $getRoomCheckIn = $request->selectedRooms;
      
        $roomCheckIn    = BookedRoom::whereIn('id', $getRoomCheckIn)->select('id', 'booking_id', 'room_id', 'room_type_id', 'fare', 'check_in_at', 'option_room')->get();
        $arrCheckIn     = $roomCheckIn->pluck('id');
        $checkInDate       = $roomCheckIn[0]['check_in_at'];
        if( $checkInDate  !== null && $roomCheckIn[0]['option_room'] === 'gio'){

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
        if($booking['user_id']){
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
}
