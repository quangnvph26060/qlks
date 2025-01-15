<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookedRoom;
use App\Models\CheckInRoom;
use App\Models\Customer;
use App\Models\RoomType;
use App\Models\Room;
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
        $pageTitle = 'Đặt phòng';
        $roomTypes = RoomType::active()->get(['id', 'name']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $userList = Customer::select('customer_code')->get();
        return view('admin.booking.book', compact('pageTitle', 'roomTypes', 'countries', 'userList'));
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
      //  \Log::info($request->all());
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                // 'room_type_id'    => 'required|integer|gt:0',
                'guest_type'      => 'required|in:1,0',
                'guest_name'      => 'nullable|required_if:guest_type,0',
                'email'           => 'required|email',
                'mobile'          => 'nullable|required_if:guest_type,0|regex:/^([0-9]*)$/',
                'address'         => 'nullable|required_if:guest_type,0|string',
                'room'            => 'required|array',
                'paid_amount'     => 'nullable|numeric|gte:0' // tiền mà khách đã thanh toán trước
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()]);
            }
            $guest = [];

            // check thông tin  user đã đăng ký tài khoản chưa
            if ($request->guest_type == 1) {
                $user = User::where('email', $request->email)->first();
                if (!$user) {
                    return response()->json(['error' => 'Không có khách đã đăng ký nào được tìm thấy với email này']); // No registered guest found with this email
                }
            } else {
                $guest['name'] = $request->guest_name;
                $guest['email'] = $request->email;
                $guest['mobile'] = $request->mobile;
                $guest['address'] = $request->address;
            }

            $bookedRoomData = [];
            $totalFare      = 0;
            $tax            = gs('tax'); // thuế

            foreach ($request->room as $room) {
                $data      = [];
                $roomTypeId     = explode('-', $room)[0]; // loai phong
                $roomId         = explode('-', $room)[1]; // phong
                $bookedFor      = explode('-', $room)[2]; // thoi gian dat phong
                $timeCheckOut   = explode('-', $room)[3]; // thoi gian tra phong
                $optionRoom     = explode('-', $room)[4]; // option gio ngay dem
                $isBookedRoom = BookedRoom::where('room_id', $roomId)
                    ->whereDate('booked_for', Carbon::parse($bookedFor))
                    ->whereIn('status', [Status::BOOKED_ROOM_ACTIVE, Status::BOOKED_ROOM_CANCELED])
                    ->where(function ($query) use ($bookedFor) {
                        $query->whereRaw('? between check_in and check_out', [Carbon::parse($bookedFor)]);
                    })
                    ->first();

                $isBookedCheckInRoom     = CheckInRoom::where('room_id', $roomId)
                    ->whereDate('booked_for', Carbon::parse($bookedFor))
                    ->where('status', [Status::BOOKED_ROOM_ACTIVE, Status::BOOKED_ROOM_CANCELED])
                    ->where(function ($query) use ($bookedFor) {
                        $query->whereRaw('? between check_in and check_out', [Carbon::parse($bookedFor)]);
                    })
                    ->first();  
                if ($isBookedRoom || $isBookedCheckInRoom) {
                    $errorDetails = [];
                    if ($isBookedRoom) {
                        $errorDetails['bookedRoom'] = $isBookedRoom;
                    }
                    if ($isBookedCheckInRoom) {
                        $errorDetails['checkInRoom'] = $isBookedCheckInRoom;
                    }
                   //    DB::rollBack();
                   //\Log::info($errorDetails['checkInRoom']['room_id']);
                    return response()->json([
                        'error' => 'Phòng đã được đặt',
                        'details' => $errorDetails, // Trả thông tin bản ghi
                    ]);
                }

                $room = Room::with('roomType','roomType.roomTypePrice')->find($roomId);
                if (!$room->is_clean) {
                    return response()->json(['error' => 'Phòng chưa dọn dẹp']);
                }
                //  \Log::info( '$room->is_clean: '.  @$room->is_clean);
                // \Log::info( '$room->roomType->id: '.  @$room->roomType->id);
                //  \Log::info('$request->room_type_id: '. $request->room_type_id);
                if ($roomTypeId != @$room->roomType->id) {
                    DB::rollBack();
                    return response()->json(['error' => 'Loại phòng đã chọn không hợp lệ ']);
                }
                $price = $room->roomType->roomTypePrice['unit_price'];
              
                //  $totalAmount = $request->total_amount;

                // Loại bỏ dấu chấm nếu tồn tại và chuyển thành số nguyên
                //  $processedAmount = is_numeric($totalAmount) ? intval(floatval(str_replace('.', '', $totalAmount))) : $totalAmount;
                //  \Log::info($processedAmount);
                $data['booking_id']       = 0;
                $data['room_type_id']     = $room->room_type_id;
                $data['room_id']          = $room->id;
                $data['booked_for']       = Carbon::parse($bookedFor)->format('Y-m-d H:i:s');
                // $data['fare']             = $room->roomPricesActive[0]['price'];
                $data['fare']             = $price;
                // $data['tax_charge']       = $room->roomPricesActive[0]['price'] * $tax / 100;
                // $data['tax_charge']       = $processedAmount * $tax / 100;
                $data['cancellation_fee'] = $room->cancellation_fee; // phí hủy bỏ nếu hủy phòng thì: tiền hoàn lại =  fare - cancellation_fee
                $data['status']           = Status::ROOM_ACTIVE;
                $data['key_status']       = Status::KEY_NOT_GIVEN;
                $data['option_room']      = $optionRoom;
                $data['created_at']       = now();
                $data['updated_at']       = now();
                $data['check_in']         = Carbon::parse($bookedFor)->format('Y-m-d H:i:s');
                $data['check_out']        = Carbon::parse($timeCheckOut)->format('Y-m-d H:i:s');
                $data['unit_code']        = hf('ma_coso');
                $check_key_status = $this->check_btn_validation($request->check_btn);
                if($check_key_status){
                    $data['key_status']    =  Status::KEY_GIVEN;
                    $data['check_in_at']   =  now();
                }
                $bookedRoomData[] = $data;

                 $totalFare += $price;
                // $totalFare += $processedAmount;
               // $totalFare = 0;
            }


            // $taxCharge = $totalFare * $tax / 100;

            // if ($request->paid_amount && $request->paid_amount > $totalFare + $taxCharge) {
            //     DB::rollBack();
            //     return response()->json(['error' => 'Số tiền thanh toán không được lớn hơn tổng số tiền']);
            // }

            $booking                 = new Booking();
            $booking->booking_number = getTrx();
            $booking->user_id        = @$user->id ?? 0;
            $booking->guest_details  = $guest;
            //  $booking->tax_charge     = $taxCharge; // thuế
            $booking->booking_fare   = $totalFare;
            $booking->paid_amount    = $request->paid_amount ?? 0;
            $booking->status         = Status::BOOKING_ACTIVE;
            // $booking->option         = $request->optionRoom;
            $booking->total_people   = $request->total_people;
            $booking->note           = $request->ghichu;
            $booking->unit_code      = hf('ma_coso');
            $booking->document_date  = now();
            $booking->save();

            if ($request->paid_amount) {
                $booking->createPaymentLog($booking->paid_amount, 'RECEIVED');
            }

            $booking->createActionHistory('book_room');

            foreach ($bookedRoomData as $key => $bookedRoom) {
                $bookedRoomData[$key]['booking_id'] = $booking->id;
            }
            $model = $check_key_status ? CheckInRoom::class : BookedRoom::class;

            // Chèn dữ liệu
            $model::insert($bookedRoomData);

            // Lấy giá trị `check_in` và `check_out`
            $checkIn  = $model::where('booking_id', $booking->id)->min('booked_for');
            $checkOut = $model::where('booking_id', $booking->id)->max('booked_for');

            // Cập nhật thông tin check-in cho booking
            $booking->check_in = $checkIn;
            
            //  $checkIn  = BookedRoom::where('booking_id', $booking->id)->min('booked_for');
            //  $checkout = BookedRoom::where('booking_id', $booking->id)->max('booked_for');
            //  $booking->check_in = $checkIn;
         
            if ($request->is_method === "receptionist") {
                $booking->check_out = Carbon::parse($request->checkOutDate)->format('Y-m-d H:i:s');
            } else {
                $booking->check_out = Carbon::parse($checkOut)->addDay()->toDateString();
            }
          $booking->save();
            $this->add_guest($request->all());

            DB::commit();

            return response()->json(['success' => 'Đặt phòng thành công']);
        } catch (\Exception $e) {
            \Log::info('Error booking : ' . $e->getMessage());
            DB::rollBack();
            return response()->json(['error' => 'Đã xảy ra lỗi, không đặt phòng thành công ']);
        }
    }

    public function add_guest($data){
        // Kiểm tra nếu email đã tồn tại
        $existingUser = User::where('email', $data['email'])->first();

        if (!$existingUser) {
            // Tách họ và tên
            $nameParts = explode(' ', $data['guest_name']);
            $lastName = array_shift($nameParts);
            $firstName = implode(' ', $nameParts);
            $username = preg_replace('/[^a-z0-9]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $data['guest_name']));

            // Tạo user mới
            User::create([
                'firstname' => $firstName,
                'lastname' => $lastName,
                'username' => $username,
                'email' => $data['email'],
                'password' => bcrypt('123456'),
                'mobile' => $data['mobile'],
                'address' => $data['address'],
                'country_code' => 'VN',
                'country_name' => 'Vietnam',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }


    public function updatenote(Request $request){
        $booking = Booking::find($request->id);
        $booking->note = $request->note;
        $booking->save();
        return response()->json(['success' => 'Cập nhật ghi chú thành công']);
    }

}
