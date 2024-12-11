<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookedRoom;
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
        return view('admin.booking.book', compact('pageTitle', 'roomTypes', 'countries'));
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

    public function book(Request $request)
    {
         \Log::info($request->all());
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'room_type_id'    => 'required|integer|gt:0',
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
                $roomId    = explode('-', $room)[0];
                $bookedFor = explode('-', $room)[1];
                $isBooked  = BookedRoom::where('room_id', $roomId)->whereDate('booked_for', Carbon::parse($bookedFor))->where('status',[Status::BOOKED_ROOM_ACTIVE,Status::BOOKED_ROOM_CANCELED])->exists();

                if ($isBooked) {
                    DB::rollBack();
                    return response()->json(['error' => 'Phòng đã được đặt']);
                }

                $room = Room::with('roomType')->find($roomId);

                if($request->is_method === "receptionist"){

                }else{

                }
                //   \Log::info('room:' .$room->roomPriceNow());
                if(!$room->is_clean){
                    return response()->json(['error' => 'Phòng chưa dọn dẹp']);
                }
                //  \Log::info( '$room->is_clean: '.  @$room->is_clean);
                //  \Log::info( '$room->roomType->id: '.  @$room->roomType->id);
                //  \Log::info('$request->room_type_id: '. $request->room_type_id);
                if ($request->room_type_id != @$room->roomType->id) {
                    DB::rollBack();
                    return response()->json(['error' => 'Loại phòng đã chọn không hợp lệ ']);
                }
                
                $totalAmount = $request->total_amount;

                // Loại bỏ dấu chấm nếu tồn tại và chuyển thành số nguyên
                $processedAmount = is_numeric($totalAmount) ? intval(floatval(str_replace('.', '', $totalAmount))) : $totalAmount;
                //  \Log::info($processedAmount);
                $data['booking_id']       = 0;
                $data['room_type_id']     = $room->room_type_id;
                $data['room_id']          = $room->id;
                $data['booked_for']       = Carbon::parse($bookedFor)->format('Y-m-d H:i:s');
                // $data['fare']             = $room->roomPricesActive[0]['price'];
                $data['fare']             = $processedAmount;
                // $data['tax_charge']       = $room->roomPricesActive[0]['price'] * $tax / 100;
                $data['tax_charge']       = $processedAmount * $tax / 100;
                $data['cancellation_fee'] = $room->cancellation_fee; // phí hủy bỏ nếu hủy phòng thì: tiền hoàn lại =  fare - cancellation_fee
                $data['status']           = Status::ROOM_ACTIVE;
                $data['created_at']       = now();
                $data['updated_at']       = now();

                $bookedRoomData[] = $data;

                // $totalFare += $room->roomPricesActive[0]['price'];
                $totalFare += $processedAmount;
            }


            $taxCharge = $totalFare * $tax / 100;

            if ($request->paid_amount && $request->paid_amount > $totalFare + $taxCharge) {
                DB::rollBack();
                return response()->json(['error' => 'Số tiền thanh toán không được lớn hơn tổng số tiền']);
            }

            $booking                 = new Booking();
            $booking->booking_number = getTrx();
            $booking->user_id        = @$user->id ?? 0;
            $booking->guest_details  = $guest;
            $booking->tax_charge     = $taxCharge;
            $booking->booking_fare   = $totalFare;
            $booking->paid_amount    = $request->paid_amount ?? 0;
            $booking->status         = Status::BOOKING_ACTIVE;
            $booking->option         =  $request->optionRoom;
            $booking->save();

            if ($request->paid_amount) {
                $booking->createPaymentLog($booking->paid_amount, 'RECEIVED');
            }

            $booking->createActionHistory('book_room');

            foreach ($bookedRoomData as $key => $bookedRoom) {
                $bookedRoomData[$key]['booking_id'] = $booking->id;
            }

            BookedRoom::insert($bookedRoomData);
            $checkIn  = BookedRoom::where('booking_id', $booking->id)->min('booked_for');
            $checkout = BookedRoom::where('booking_id', $booking->id)->max('booked_for');
           
            $booking->check_in = $checkIn;

            if($request->is_method === "receptionist"){
                $booking->check_out = Carbon::parse($request->checkOutTime)->format('Y-m-d H:i:s');
            }else{
                $booking->check_out = Carbon::parse($checkout)->addDay()->toDateString();
            }
            $booking->save();

            DB::commit();

            return response()->json(['success' => 'Đặt phòng thành công']);
        } catch (\Exception $e) {
           \Log::info('Error booking : '. $e->getMessage());
            DB::rollBack();
            return response()->json(['error' => 'Đã xảy ra lỗi, không đặt phòng thành công ']);
        }
    }
}
