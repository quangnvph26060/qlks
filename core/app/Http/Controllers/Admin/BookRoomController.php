<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookedRoom;
use App\Models\CheckIn;
use App\Models\CheckInRoom;
use App\Models\Customer;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\RoomStatusHistory;
use App\Models\RoomTypePrice;
use App\Models\User;
use App\Traits\BookingActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookRoomController extends Controller
{
    use BookingActions;

    public function room()
    {
        $pageTitle = ' Danh sách đặt phòng';
        return view('admin.booking.book', compact('pageTitle'));
    }

    public function getBooking(Request $request)
    {
        $perPage = 10;
        $roomBookings = RoomBooking::active()->with('room', 'admin')->where('unit_code',unitCode())->orderBy('created_at', 'asc')->paginate($perPage);
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
    public function getDates($startDate, $endDate)
    {
        $dates = [];
        $currentDate = Carbon::parse($startDate);

        while ($currentDate->lte(Carbon::parse($endDate))) {
            $dates[] = $currentDate->toDateString();
            $currentDate->addDay();
        }

        return $dates;
    }
    // check in
    public function checkIn(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $roomBooking =  RoomBooking::find($id);

            if (!$roomBooking) {
                return response()->json(['error' => 'Không tìm thấy  mã đặt phòng']);
            }
          
            $checkIn = CheckIn::where('id_room_booking',$roomBooking->booking_id)->get();
            $flag = true; 
            $now = now()->format('Y-m-d');
            if($checkIn->isEmpty()){
                $checkIn = CheckIn::where('room_code',$roomBooking->room_code)->get();
            }
            foreach($checkIn as $item){
                
                if($item->id_room_booking === $roomBooking->booking_id){
                    if($item->room_code === $roomBooking->room_code){
                        $dateRoomBooking = $this->getDates($item->checkin_date, $item->checkout_date);
                        if (in_array($now, $dateRoomBooking)) {
                            $flag = false;
                            break;
                        }else{
                            $flag = true;
                        }
                    }
                   
                }
                 if($item->id_room_booking !== $roomBooking->booking_id){
                    $dateRoomBooking = $this->getDates($item->checkin_date, $item->checkout_date);
                    if (in_array($now, $dateRoomBooking)) {
                        $flag = false;
                        break;
                    }else{
                        $flag = true;
                    }
                }

            }
            if($flag){  

                $roomBooking->status = Status::BOOKED_ROOM_ACTIVE;
                $roomBooking->save();

                $check_in                     =  new CheckIn();
                $check_in->check_in_id        = getCode('NP', 12); // ID đặt phòng
                $check_in->id_room_booking    = $roomBooking->booking_id;               // ID phòng đặt (nếu có)
                $check_in->room_code          = $roomBooking->room_code;                // Mã phòng
                $check_in->document_date      = $roomBooking->document_date;      // Ngày chứng từ
                $check_in->checkin_date       = now();        // Ngày nhận
                $check_in->checkout_date      = $roomBooking->checkout_date;       // Ngày trả
                $check_in->customer_code      = $roomBooking->customer_code;        // Mã khách hàng (nếu có)
                $check_in->customer_name      = $roomBooking->customer_name;    // Tên khách hàng
                $check_in->phone_number       = $roomBooking->phone_number;       // Số điện thoại
                $check_in->email              = $roomBooking->email;    // Email
                $check_in->price_group        = $roomBooking->price_group;                     // Nhóm giá (nếu có)
                $check_in->guest_count        = $roomBooking->guest_count;                   // Số người
                $check_in->total_amount       = $roomBooking->total_amount;          // Thành tiền
                $check_in->deposit_amount     = $roomBooking->deposit_amount;        // Đặt cọc
                $check_in->note               = $roomBooking->note;        // Ghi chú (nếu có)
                $check_in->user_source        = $roomBooking->user_source;          // Nguồn khách (nếu có)
                $check_in->unit_code          = hf('ma_coso');
                $check_in->created_by         = authAdmin()->id;             // Người tạo

                $check_in->save();
                DB::commit();
                return response()->json(['status' => 'success', 'success' => 'Nhận phòng thành công']);
            }else{
                DB::commit();
                return response()->json(['status' => 'error', 'success' => 'Nhận không phòng thành công']);
            }
           

            // $roomstatus = new RoomStatusHistory();
            // $roomstatus->room_id  = $room['room'];
            // $roomstatus->start_date   = Carbon::parse($room['dateIn']);
            // $roomstatus->end_date  = Carbon::parse($room['dateOut']);
            // $roomstatus->unit_code  = hf('ma_coso');
            // $roomstatus->created_at = now();

            // if($request->method == 'check_in'){
            //     $roomstatus->status_code  = 3;

            // }else{
            //     $roomstatus->status_code  = 2;
            // }
            // response
          
        } catch (\Exception $e) {
            \Log::info('Error booking : ' . $e->getMessage());
            DB::rollBack();
            return response()->json(['error' => 'Đã xảy ra lỗi, không đặt phòng thành công ']);
        }
    }

    function searchRoom(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_type' => 'required|exists:room_types,id',
            'date' => 'required|string',
            'rooms' => 'required|integer|gt:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $date = explode('-', $request->date);

        $request->merge([
            'checkin_date'  => trim(@$date[0]),
            'checkout_date' => trim(@$date[1]),
        ]);

        $validator = Validator::make($request->all(), [
            'checkin_date'  => 'required|date_format:m/d/Y|after:yesterday',
            'checkout_date' => 'required|date_format:m/d/Y|after:checkin_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $view = $this->getRooms($request);

        return response()->json(['html' => $view]);
    }
    private function check_btn_validation($check_btn)
    {
        if ($check_btn === 'checkin') {
            return true;
        }

        return false;
    }
    public function book(Request $request)
    {

        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                // 'room_type_id'    => 'required|integer|gt:0',
                // 'guest_type'      => 'required|in:1,0',
                // 'guest_name'      => 'nullable|required_if:guest_type,0',
                // 'email'           => 'required|email',
                // 'mobile'          => 'nullable|required_if:guest_type,0|regex:/^([0-9]*)$/',
                // 'address'         => 'nullable|required_if:guest_type,0|string',
                'name'      => 'nullable|required_if:guest_type,0',
                // 'room'            => 'required|array',
                // 'paid_amount'     => 'nullable|numeric|gte:0' // tiền mà khách đã thanh toán trước
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()]);
            }
            $guest = [];

            // check thông tin  user đã đăng ký tài khoản chưa
            // if ($request->guest_type == 1) {
            //     $user = User::where('email', $request->email)->first();
            //     if (!$user) {
            //         return response()->json(['error' => 'Không có khách đã đăng ký nào được tìm thấy với email này']); // No registered guest found with this email
            //     }
            // } else {
            //     $guest['name'] = $request->guest_name;
            //     $guest['email'] = $request->email;
            //     $guest['mobile'] = $request->mobile;
            //     $guest['address'] = $request->address;
            // }
            $bookingId = null;
            // $bookedRoomData = [];
            // $totalFare      = 0;
            $tax            = gs('tax'); // thuế
            foreach ($request->room as $index => $item) {
                $room = json_decode($item, true);
                // kiểm tra khách hàng
                $customer = $this->add_guest($request->name, $request->phone);
                // \Log::info($request->all());
                // \Log::info($customer);
                // đặt cọc của từng phòng
                $depositAmount = is_numeric($room['deposit']) ? intval(floatval(str_replace('.', '', $room['deposit']))) : $room['deposit'];

                $roomPice = RoomTypePrice::where('room_type_id', $room['roomType'])->orderByDesc('price_validity_period')->first();

                $check_in = $request->method == 'check_in' ? new CheckIn() : new RoomBooking();

                if ($index == 0) {
                    if ($request->method == 'check_in') {
                        $check_in->check_in_id      = getCode('NP', 12);
                        $bookingId = $check_in->check_in_id;
                    } else {
                        $check_in->booking_id       = getCode('DP', 12);
                        $bookingId = $check_in->booking_id;
                    }
                } else {
                    if ($request->method == 'check_in') {
                        $check_in->check_in_id   = $bookingId;
                    } else {
                        $check_in->booking_id = $bookingId;
                    }
                }

                $roomstatus = new RoomStatusHistory();
                $roomstatus->room_id  = $room['room'];
                $roomstatus->start_date   = Carbon::parse($room['dateIn']);
                $roomstatus->end_date  = Carbon::parse($room['dateOut']);
                $roomstatus->unit_code  = hf('ma_coso');
                $roomstatus->created_at = now();

                if($request->method == 'check_in'){
                    $roomstatus->status_code  = 3;

                }else{
                    $roomstatus->status_code  = 2;
                }
                $roomstatus->save();

                $check_in->room_code      = $room['room'];
                $check_in->document_date  = now();
                $check_in->checkin_date   = Carbon::parse($room['dateIn']);
                $check_in->checkout_date  = Carbon::parse($room['dateOut']);
                $check_in->customer_code  = $customer['customer_code'];
                $check_in->customer_name  = $customer['name'];
                $check_in->phone_number   = $customer['phone'];
                $check_in->email          = $customer['email'];
                $check_in->price_group    = 1; // đang fix cứng
                $check_in->guest_count    = $room['adult'];
                $check_in->total_amount   = $roomPice['unit_price']; // giá phòng hiện tại đang áp dụng
                $check_in->deposit_amount = $depositAmount;
                $check_in->note           = $room['note'];
                $check_in->user_source    = 'FB';
                $check_in->unit_code      = hf('ma_coso');
                $check_in->created_by     = authAdmin()->id;

                $check_in->save();
                // $data      = [];
                // $roomTypeId     = explode('-', $room)[0]; // loai phong
                // $roomId         = explode('-', $room)[1]; // phong
                // $bookedFor      = explode('-', $room)[2]; // thoi gian dat phong
                // $timeCheckOut   = explode('-', $room)[3]; // thoi gian tra phong
                // $optionRoom     = explode('-', $room)[4]; // option gio ngay dem
                // $isBookedRoom = BookedRoom::where('room_id', $roomId)
                //     ->whereDate('booked_for', Carbon::parse($bookedFor))
                //     ->whereIn('status', [Status::BOOKED_ROOM_ACTIVE, Status::BOOKED_ROOM_CANCELED])
                //     ->where(function ($query) use ($bookedFor) {
                //         $query->whereRaw('? between check_in and check_out', [Carbon::parse($bookedFor)]);
                //     })
                //     ->first();

                // $isBookedCheckInRoom     = CheckInRoom::where('room_id', $roomId)
                //     ->whereDate('booked_for', Carbon::parse($bookedFor))
                //     ->where('status', [Status::BOOKED_ROOM_ACTIVE, Status::BOOKED_ROOM_CANCELED])
                //     ->where(function ($query) use ($bookedFor) {
                //         $query->whereRaw('? between check_in and check_out', [Carbon::parse($bookedFor)]);
                //     })
                //     ->first();
                // if ($isBookedRoom || $isBookedCheckInRoom) {
                //     $errorDetails = [];
                //     if ($isBookedRoom) {
                //         $errorDetails['bookedRoom'] = $isBookedRoom;
                //     }
                //     if ($isBookedCheckInRoom) {
                //         $errorDetails['checkInRoom'] = $isBookedCheckInRoom;
                //     }
                //    //    DB::rollBack();
                //    //\Log::info($errorDetails['checkInRoom']['room_id']);
                //     return response()->json([
                //         'error' => 'Phòng đã được đặt',
                //         'details' => $errorDetails, // Trả thông tin bản ghi
                //     ]);
                // }

                // $room = Room::with('roomType','roomType.roomTypePrice')->find($roomId);
                // if (!$room->is_clean) {
                //     return response()->json(['error' => 'Phòng chưa dọn dẹp']);
                // }
                // //  \Log::info( '$room->is_clean: '.  @$room->is_clean);
                // // \Log::info( '$room->roomType->id: '.  @$room->roomType->id);
                // //  \Log::info('$request->room_type_id: '. $request->room_type_id);
                // if ($roomTypeId != @$room->roomType->id) {
                //     DB::rollBack();
                //     return response()->json(['error' => 'Loại phòng đã chọn không hợp lệ ']);
                // }
                // $price = $room->roomType->roomTypePrice['unit_price'];

                //    $totalAmount = $room['deposit'];

                // Loại bỏ dấu chấm nếu tồn tại và chuyển thành số nguyên
                //  $processedAmount = is_numeric($totalAmount) ? intval(floatval(str_replace('.', '', $totalAmount))) : $totalAmount;
                //  \Log::info($processedAmount);
                // $data['booking_id']       = 0;
                // $data['room_type_id']     = $room->room_type_id;
                // $data['room_id']          = $room->id;
                // $data['booked_for']       = Carbon::parse($bookedFor)->format('Y-m-d H:i:s');
                // // $data['fare']             = $room->roomPricesActive[0]['price'];
                // $data['fare']             = $price;
                // // $data['tax_charge']       = $room->roomPricesActive[0]['price'] * $tax / 100;
                // // $data['tax_charge']       = $processedAmount * $tax / 100;
                // $data['cancellation_fee'] = $room->cancellation_fee; // phí hủy bỏ nếu hủy phòng thì: tiền hoàn lại =  fare - cancellation_fee
                // $data['status']           = Status::ROOM_ACTIVE;
                // $data['key_status']       = Status::KEY_NOT_GIVEN;
                // $data['option_room']      = $optionRoom;
                // $data['created_at']       = now();
                // $data['updated_at']       = now();
                // $data['check_in']         = Carbon::parse($bookedFor)->format('Y-m-d H:i:s');
                // $data['check_out']        = Carbon::parse($timeCheckOut)->format('Y-m-d H:i:s');
                // $data['unit_code']        = hf('ma_coso');
                // $check_key_status = $this->check_btn_validation($request->check_btn);
                // if($check_key_status){
                //     $data['key_status']    =  Status::KEY_GIVEN;
                //     $data['check_in_at']   =  now();
                // }
                // $bookedRoomData[] = $data;

                //  $totalFare += $price;
                // $totalFare += $processedAmount;
                // $totalFare = 0;
            }


            // $taxCharge = $totalFare * $tax / 100;

            // if ($request->paid_amount && $request->paid_amount > $totalFare + $taxCharge) {
            //     DB::rollBack();
            //     return response()->json(['error' => 'Số tiền thanh toán không được lớn hơn tổng số tiền']);
            // }

            // $booking                 = new Booking();
            // $booking->booking_number = getTrx();
            // $booking->user_id        = @$user->id ?? 0;
            // $booking->guest_details  = $guest;
            // //  $booking->tax_charge     = $taxCharge; // thuế
            // $booking->booking_fare   = $totalFare;
            // $booking->paid_amount    = $request->paid_amount ?? 0;
            // $booking->status         = Status::BOOKING_ACTIVE;
            // // $booking->option         = $request->optionRoom;
            // $booking->total_people   = $request->total_people;
            // $booking->note           = $request->ghichu;
            // $booking->unit_code      = hf('ma_coso');
            // $booking->document_date  = now();
            // $booking->save();

            // lịch sử thanh toán tiền cọc
            // if ($request->paid_amount) {
            //     $booking->createPaymentLog($booking->paid_amount, 'RECEIVED');
            // }

            // $booking->createActionHistory('book_room');

            // foreach ($bookedRoomData as $key => $bookedRoom) {
            //     $bookedRoomData[$key]['booking_id'] = $booking->id;
            // }
            // $model = $check_key_status ? CheckInRoom::class : BookedRoom::class;

            // Chèn dữ liệu
            // $model::insert($bookedRoomData);

            // // Lấy giá trị `check_in` và `check_out`
            // $checkIn  = $model::where('booking_id', $booking->id)->min('booked_for');
            // $checkOut = $model::where('booking_id', $booking->id)->max('booked_for');

            // Cập nhật thông tin check-in cho booking
            // $booking->check_in = $checkIn;

            //  $checkIn  = BookedRoom::where('booking_id', $booking->id)->min('booked_for');
            //  $checkout = BookedRoom::where('booking_id', $booking->id)->max('booked_for');
            //  $booking->check_in = $checkIn;

            // if ($request->is_method === "receptionist") {
            //     $booking->check_out = Carbon::parse($request->checkOutDate)->format('Y-m-d H:i:s');
            // } else {
            //     $booking->check_out = Carbon::parse($checkOut)->addDay()->toDateString();
            // }
            // $booking->save();


            DB::commit();

            return response()->json(['success' => 'Đặt phòng thành công']);
        } catch (\Exception $e) {
            \Log::info('Error booking : ' . $e->getMessage());
            DB::rollBack();
            return response()->json(['error' => 'Đã xảy ra lỗi, không đặt phòng thành công ']);
        }
    }
    // check in, out xem các đơn hàng ko được cùng ngày nhận trà bằng nhau 
    public function bookEdit(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator      = Validator::make($request->all(), [
                'name'      => 'nullable|required_if:guest_type,0',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()]);
            }
            $guest = [];
            $bookingId = null;
            // $bookedRoomData = [];
            // $totalFare      = 0;
            $tax            = gs('tax'); // thuế
            foreach ($request->room as $index => $item) {
                $room = json_decode($item, true);
                // kiểm tra khách hàng
                $customer = $this->add_guest($request->name, $request->phone);
                // đặt cọc của từng phòng
                $depositAmount = is_numeric($room['deposit']) ? intval(floatval(str_replace('.', '', $room['deposit']))) : $room['deposit'];

                $roomPice = RoomTypePrice::where('room_type_id', $room['roomType'])->orderByDesc('price_validity_period')->first();

                $check_in = $request->method == 'check_in' ?  CheckIn::query() :  RoomBooking::query()->active();
                $check_in = $check_in->where('id',$room['bookingId'])->first();
                if ($check_in) {
                    $check_in->room_code      = $room['room'];
                    $check_in->document_date  = now();
                    $check_in->checkin_date   = Carbon::parse($room['dateIn']);
                    $check_in->checkout_date  = Carbon::parse($room['dateOut']);
                    $check_in->customer_code  = $customer['customer_code'];
                    $check_in->customer_name  = $customer['name'];
                    $check_in->phone_number   = $customer['phone'] ?? $request->phone;
                    $check_in->email          = $customer['email'];
                    $check_in->price_group    = 1; // đang fix cứng
                    $check_in->guest_count    = $room['adult'];
                    $check_in->total_amount   = $roomPice['unit_price']; // giá phòng hiện tại đang áp dụng
                    $check_in->deposit_amount = $depositAmount;
                    $check_in->note           = $room['note'];
                    $check_in->user_source    = 'FB';
                    $check_in->unit_code      = hf('ma_coso');
                    $check_in->created_by     = authAdmin()->id;

                    $check_in->save();
                }
            }
            DB::commit();
            return response()->json(['success' => 'Cập nhật đặt phòng thành công']);
        } catch (\Exception $e) {
            \Log::info('Error booking : ' . $e->getMessage());
            DB::rollBack();
            return response()->json(['error' => 'Đã xảy ra lỗi, không đặt phòng thành công ']);
        }
    }
    protected function add_guest($name, $phone)
    {
        // Kiểm tra nếu email đã tồn tại
        $existingUser = Customer::where('name', $name);
        // if (!is_null($phone)) {
        //     $existingUser->orWhere('phone', $phone);
        // }
        $existingUser = $existingUser->first();
        // \Log::info($existingUser);
        if ($existingUser) {
            // Nếu tồn tại, cập nhật thông tin
            $existingUser->update([
                'name'      => $name,
                'phone'     => $phone,
                'updated_at' => now()
            ]);
        } else {
            // Nếu chưa tồn tại, tạo mới
            $existingUser = Customer::create([
                'customer_code' => getCode('KH', 6),
                'name'          => $name,
                'phone'         => $phone,
                'unit_code'     => hf('ma_coso'),
                'created_at'    => now(),
                'updated_at'    => now()
            ]);
        }
        return $existingUser;
    }


    public function updatenote(Request $request)
    {
        $booking = Booking::find($request->id);
        $booking->note = $request->note;
        $booking->save();
        return response()->json(['success' => 'Cập nhật ghi chú thành công']);
    }

    public function searchCustomer(Request $request)
    {
        $data = Customer::where('name', $request->name)->first();
        return response()->json([
            'status' => 'success',
            'data'   => $data
        ]);
    }
    // sửa phòng
    public function roomBookingEdit($id)
    {
        $roomBookings = RoomBooking::with('room','room.roomType','room.roomType.roomTypePrice','room.roomType.roomTypePrice.setupPricing')
        ->where('booking_id',$id)->get();
        $groupedBookings = [];
        foreach ($roomBookings as $booking) {
            $key = $booking->customer_code . '|' . $booking->customer_name . '|' . $booking->email;
            if (!isset($groupedBookings[$key])) {
                $groupedBookings[$key] = [
                    'customer_code' => $booking->customer_code,
                    'customer_name' => $booking->customer_name,
                    'email' => $booking->email,
                    'phone_number' => $booking->phone_number,
                    'room_bookings' => [],
                ];
            }
            $groupedBookings[$key]['room_bookings'][] = [
                'id'                => $booking->id,
                'booking_id'        => $booking->booking_id,
                'checkin_date'      => $booking->checkin_date,
                'checkout_date'     => $booking->checkout_date,
                'total_amount'      => $booking->total_amount,
                'deposit_amount'    => $booking->deposit_amount,
                'note'              => $booking->note,
                'room_id'           => $booking->room->id,
                'room_type_id'      => $booking->room->room_type_id,
                'room_number'       => $booking->room->room_number,
                'guest_count'       => $booking->guest_count,
                'status'            => $booking->status,
            ];
        }
        // Chuyển về dạng danh sách thay vì array với key
        $groupedBookings = array_values($groupedBookings);
        return response()->json(['status' => 'success', 'data' => $groupedBookings]);
    }
}
