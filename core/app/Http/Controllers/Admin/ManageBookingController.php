<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookingActionHistory;
use App\Models\Deposit;
use App\Models\PaymentLog;
use App\Models\UsedPremiumService;
use App\Traits\BookingActions;
use Illuminate\Support\Carbon;
use PDF;
use App\Http\Responses\ApiResponse;
use App\Models\BookedRoom;
use App\Models\CheckInRoom;

class ManageBookingController extends Controller
{
    use BookingActions;

    public function handoverKey(Request $request,  $id) // bàn giao chìa khóa
    {
       
        // [2025-01-06 17:18:27] local.INFO: array (
        //     'is_method' => 'receptionist',
        //     'room_id' => '38',
        //     'room_type_id' => '5',
        //     'price_booked' => NULL,
        //     'booked_arr' => 
        //     array (
        //       0 => '268',
        //     ),
        //   )  
        \Log::info($request->all());
        $booking = Booking::active()->findOrFail($id);
        if(!$booking){
            $notify[] = ['error', 'Không tìm thấy thông tin phòng'];
        }
        $booked_arr = $request->booked_arr;
        foreach($booked_arr as $bookroom){
            $data      = [];
            $bookedRoom = BookedRoom::find($bookroom);
          //  \Log::info($bookedRoom['room_type_id']);
            if (!$bookedRoom) {
                continue;
            }
            $data['booking_id']       = $bookedRoom['booking_id'];
            $data['room_type_id']     = $bookedRoom['room_type_id'];
            $data['room_id']          = $bookedRoom['room_id'];
            $data['booked_for']       = now();
            // $data['fare']             = $room->roomPricesActive[0]['price'];
            $data['fare']             = $bookedRoom['fare'];
            // $data['tax_charge']       = $room->roomPricesActive[0]['price'] * $tax / 100;
            // $data['tax_charge']       = $processedAmount * $tax / 100;
            $data['cancellation_fee'] = $bookedRoom['cancellation_fee']; // phí hủy bỏ nếu hủy phòng thì: tiền hoàn lại =  fare - cancellation_fee
            $data['status']           = Status::ROOM_ACTIVE;
            $data['key_status']       = Status::KEY_NOT_GIVEN;
            $data['option_room']      = $bookedRoom['option_room'];
            $data['check_in']         = now();
            $data['check_out']        = Carbon::parse($bookedRoom['check_out'])->format('Y-m-d H:i:s');
            $data['unit_code']        = hf('ma_coso');
            $data['key_status']       =  Status::KEY_GIVEN;
            $data['book_room_id']     =  $bookedRoom['id'];
            $data['check_in_at']      = now();
            $data['created_at']       = now();
            $data['updated_at']       = now();

            $bookedRoomData[] = $data;
        }
        CheckInRoom::insert($bookedRoomData);
        // $bookedRoom = BookedRoom::where('booking_id',$booking->id)->where('room_id',$request->room_id)->where('room_type_id',$request->room_type_id)->first();
       
        // if ($bookedRoom->key_status == Status::ENABLE) {
        //     $notify[] = ['error', 'Chìa khóa đã được trao cho khách'];
        //     return back()->withNotify($notify);
        // }
        // if (now()->format('Y-m-d H:i:s') < $booking->booked_for) {
        //     $notify[] = ['error', 'Bạn không thể giao chìa khóa trước ngày nhận phòng'];
        //     return back()->withNotify($notify);
        // }

        // if (now()->format('Y-m-d H:i:s') < $booking->check_in) {
        //     $notify[] = ['error', 'Bạn không thể giao chìa khóa trước ngày nhận phòng'];
        //     return back()->withNotify($notify);
        // }

        // if (now()->format('Y-m-d H:i:s') >= $booking->check_out) {
        //     $notify[] = ['error', 'Bạn không thể giao chìa khóa sau ngày trả phòng'];
        //     return back()->withNotify($notify);
        // }
        if($request->price_booked > 0){
            $this->processPayment($request->price_booked, $id);
        }
        $bookedRoom->key_status     = Status::ENABLE;
        $bookedRoom->check_in_at    = now();
        $bookedRoom->save();

        $booking->checked_in_at     = now();
        $booking->save();

        $booking->createActionHistory('key_handover'); // lịch sử bàn giao chìa khóa 

        // if($request->is_method === "receptionist"){
        //     return ApiResponse::success("","",200);
        // }
        return ApiResponse::success("","",200);
        $notify[] = ['success', 'Bàn giao chìa khóa thành công'];
        return back()->withNotify($notify);
    }

    public function mergeBooking(Request $request, $id)
    {
        $parentBooking = Booking::active()->findOrFail($id);
        $request->merge(['merge_with' => $parentBooking->booking_number]);

        $request->validate([
            'booking_numbers'             => 'required|array',
            'booking_numbers.*'           => 'distinct|exists:bookings,booking_number|different:merge_with',
        ], [
            'booking_numbers.*.distinct' => 'Booking numbers should not be duplicate',
            'booking_numbers.*.different' => 'Booking numbers must be different from the booking number of merging with',
        ]);

        // Check if available to merge
        $check = Booking::whereIn('booking_number', $request->booking_numbers)->where('status', '!=', 1)->first();

        if ($check) {
            $notify[] = ['error', $check->booking_number . ' can\'t be merged. Only active bookings are able to merge.'];
            return back()->withNotify($notify);
        }

        foreach ($request->booking_numbers as $bookingNumber) {
            $booking = Booking::where('booking_number', $bookingNumber)->first();
            $booking->usedPremiumService()->update(['booking_id' => $parentBooking->id]);
            $booking->bookedRooms()->update(['booking_id' => $parentBooking->id]);
            BookingActionHistory::where('booking_id', $booking->id)->delete();
            PaymentLog::where('booking_id', $booking->id)->update(['booking_id' => $parentBooking->id]);
            $keyStatus = $parentBooking->key_status == Status::KEY_GIVEN || $booking->key_status == Status::KEY_GIVEN ? 1 : 0;
            $parentBooking->tax_charge       += $booking->tax_charge;
            $parentBooking->booking_fare     += $booking->booking_fare;
            $parentBooking->service_cost     += $booking->service_cost;
            $parentBooking->extra_charge     += $booking->extra_charge;
            $parentBooking->extra_charge_subtracted  += $booking->extra_charge_subtracted;
            $parentBooking->paid_amount      += $booking->paid_amount;
            $parentBooking->cancellation_fee += $booking->cancellation_fee;
            $parentBooking->key_status        = $keyStatus;
            $parentBooking->save();
            $booking->delete();
        }

        $lastDateBookedRoom  = $parentBooking->activeBookedRooms()->orderBy('booked_for', 'desc')->first();
        $firstDateBookedRoom = $parentBooking->activeBookedRooms()->orderBy('booked_for', 'asc')->first();

        if ($lastDateBookedRoom) {
            $parentBooking->check_out = Carbon::parse($lastDateBookedRoom->booked_for)->addDay()->format('Y-m-d');
        }

        if ($firstDateBookedRoom) {
            $parentBooking->check_in = $firstDateBookedRoom->booked_for;
        }

        $parentBooking->save();

        $detail = implode(', ', $request->booking_numbers) . ' merged with ' . $parentBooking->booking_number;

        $parentBooking->createActionHistory('merged_booking', $detail);
        $notify[] = ['success', 'Bookings merged successfully'];
        return back()->withNotify($notify);
    }

    public function paymentView($id)
    {
        $booking           = Booking::with('bookedRooms', 'payments', 'usedPremiumService', 'user')->findOrFail($id);
        $totalFare         = $booking->bookedRooms->sum('fare');
        $totalTaxCharge    = $booking->bookedRooms->sum('tax_charge');
        $canceledFare      = $booking->bookedRooms->where('status', Status::ROOM_CANCELED)->sum('fare');
        $canceledTaxCharge = $booking->bookedRooms->where('status', Status::ROOM_CANCELED)->sum('tax_charge');
        $returnedPayments  = $booking->payments->where('type', 'RETURNED');
        $receivedPayments  = $booking->payments->where('type', 'RECEIVED');
        $pageTitle         = "Thanh toán hóa đơn";
        return view('admin.booking.payment', compact('pageTitle', 'booking', 'totalFare', 'totalTaxCharge', 'canceledFare', 'canceledTaxCharge', 'returnedPayments', 'receivedPayments'));
    }
    public function processPayment($price, $id) { 
       
        $booking = Booking::findOrFail($id);
        $due     = $booking->total_amount - $booking->paid_amount;
       
        if ($price > abs($due)) {
            $message = $due <= 0 ? 'Số tiền không thể lớn hơn số tiền phải thu' : 'Số tiền không được lớn hơn số tiền phải trả';
            $notify[] = ['error', $message];
            return back()->withNotify($notify);
        }

        $is_method = 'receptionist';
        if ($due > 0) {
            return $this->receivePayment($booking, $price, $is_method);
        }
      
        return $this->returnPayment($booking, $price);
    }

    public function payment(Request $request, $id) // thanh toán
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0'
        ]);
        $booking = Booking::findOrFail($id);
        $due     = $booking->total_amount - $booking->paid_amount;
        if ($request->amount > abs($due)) {
            $message = $due <= 0 ? 'Số tiền không thể lớn hơn số tiền phải thu' : 'Số tiền không được lớn hơn số tiền phải trả';
            $notify[] = ['error', $message];
            return back()->withNotify($notify);
        }
        $is_method = $request->input('is_method', '');
        if ($due > 0) {
            return $this->receivePayment($booking, $request->amount, $is_method);
        }

        return $this->returnPayment($booking, $request->amount);
    }

    public function addExtraCharge(Request $request, $id)
    {
        $this->extraChargeValidation($request);
       
        $booking = Booking::findOrFail($id);
        $booking->extra_charge += $request->amount;
        $booking->save();
        $reason = showAmount($request->amount) . ' được thêm vào cho  ' . $request->reason; // added for

        $booking->createActionHistory('extra_charge_added', $reason);

        $notify[] = ['success', 'Đã thêm phí phụ thu thành công'];
        return back()->withNotify($notify);
    }

    public function subtractExtraCharge(Request $request, $id)
    {
        $this->extraChargeValidation($request);
       
        $booking = Booking::findOrFail($id);

        if ($request->amount + $booking->extra_charge_subtracted > $booking->extra_charge) {
            $notify[] = ['error', 'Số tiền trừ phải nhỏ hơn hoặc bằng số tiền phụ thu khi đặt phòng'];
            return back()->withNotify($notify);
        }

        $booking->extra_charge_subtracted += $request->amount;
        $booking->save();

        $reason = showAmount($request->amount) . ' subtracted for ' . $request->reason;

        $booking->createActionHistory('extra_charge_subtracted', $reason);

        $notify[] = ['success', 'Đã trừ phí phụ thu thành công'];
        return back()->withNotify($notify);
    }

    private function extraChargeValidation($request)
    {
        $request->validate([
            'amount' => 'required|numeric|gte:0',
            'reason' => 'required|string|max:255',
        ]);
    }

    public function checkOutPreview($id)
    {
        $booking           = Booking::active()->with('bookedRooms', 'payments', 'usedPremiumService', 'user')->findOrFail($id);
        $totalFare         = $booking->bookedRooms->sum('fare');
        $totalTaxCharge    = $booking->bookedRooms->sum('tax_charge');
        $canceledFare      = $booking->bookedRooms->where('status', Status::ROOM_CANCELED)->sum('fare');
        $canceledTaxCharge = $booking->bookedRooms->where('status', Status::ROOM_CANCELED)->sum('tax_charge');
        $returnedPayments  = $booking->payments->where('type', 'RETURNED');
        $receivedPayments  = $booking->payments->where('type', 'RECEIVED');
        $pageTitle = "Kiểm tra Đặt phòng";
        return view('admin.booking.check_out', compact('pageTitle', 'booking', 'totalFare', 'totalTaxCharge', 'canceledFare', 'canceledTaxCharge', 'returnedPayments', 'receivedPayments'));
    }

    public function checkOut(Request $request, $id){
        $booking = Booking::active()->with('payments')->withSum('usedPremiumService', 'total_amount')->findOrFail($id);
    }



    // public function checkOut(Request $request, $id) // trả phòng
    // {
    //     $booking = Booking::active()->with('payments')->withSum('usedPremiumService', 'total_amount')->findOrFail($id);
    //     // if ($booking->check_out > now()->toDateString()) {
    //     //     $notify[] = ['error', 'Ngày thanh toán cho đặt phòng này lớn hơn hiện tại'];
    //     //     return back()->withNotify($notify);
    //     // }
    //     $due = getAmount($booking->total_amount - $booking->paid_amount);

    //     if ($due > 0) {
    //         $notify[] = ['error', 'Khách hàng phải thanh toán số tiền phải trả trước'];
    //         return back()->withNotify($notify);
    //     }
    //     if ($due < 0) {
    //         $notify[] = ['error', 'Hoàn trả số tiền được hoàn lại cho khách trước'];
    //         return back()->withNotify($notify);
    //     }
    //     $booking->createActionHistory('checked_out');
    //     $booking->activeBookedRooms()->update(['status' => Status::BOOKING_CHECKOUT]); 
    //     $booking->status = Status::BOOKING_CHECKOUT;
    //     $booking->checked_out_at = now();
    //     $booking->save();
    //     if($request->is_method === 'receptionist')
    //     {
    //         return response()->json(['status'=>'success']);
    //     }
    //     $notify[] = ['success', 'Đặt phòng đã được thanh toán thành công'];
    //     return redirect()->route('admin.booking.all')->withNotify($notify);
    // }

    public function premiumServiceDetail(Request $request, $id)
    {
        $booking = Booking::where('id', $id)->firstOrFail();
        $services = UsedPremiumService::where('booking_id', $id)->with('premiumService', 'room', 'admin')->paginate(getPaginate());
        $pageTitle = 'Service Details - ' . $booking->booking_number;
        if($request->is_method === 'receptionist'){
            return response()->json(['status'=>'success','services'=>$services]);
        }
        return view('admin.booking.service_details', compact('pageTitle', 'services'));
    }

    public function generateInvoice($bookingId)
    {
        $booking = Booking::with([
            'activeBookedRooms' => function ($query) {
                $query->select('id', 'booking_id', 'room_id', 'fare', 'status', 'booked_for');
            },
            'activeBookedRooms.room:id,room_type_id,room_number',
            'activeBookedRooms.room.roomType:id,name',
            'usedPremiumService.room',
            'usedPremiumService.premiumService',
            'user:id,firstname,lastname,username,email,mobile'
        ])->findOrFail($bookingId);

        $data = ['booking' => $booking];

        $pdf = PDF::loadView('partials.invoice', $data);

        return $pdf->stream($booking->booking_number . '.pdf');
    }
 
    protected function receivePayment($booking, $receivingAmount, $is_method) // nhận thanh toán
    {
        $this->deposit($booking, $receivingAmount);
        $booking->createPaymentLog($receivingAmount, 'RECEIVED');
        $booking->createActionHistory('payment_received');
        $booking->paid_amount += $receivingAmount;
        $booking->save();
         \Log::info('123123 '. $is_method);
        if($is_method === 'receptionist'){
            $notify[] = ['success', 'Đã nhận thanh toán thành công'];
          // return response()->json(['status'=>'success', 'booking_id'=> $booking->getId(), 'id'=>$booking->id]);
        }
        // $notify[] = ['success', 'Đã nhận thanh toán thành công'];
        // return back()->withNotify($notify);
    }

    // protected function receivePayment($booking, $receivingAmount, $is_method) // nhận thanh toán
    // {
    //     $this->deposit($booking, $receivingAmount);
    //     $booking->createPaymentLog($receivingAmount, 'RECEIVED');
    //     $booking->createActionHistory('payment_received');
    //     $booking->paid_amount += $receivingAmount;
    //     $booking->save();
    //      \Log::info('123123 '. $is_method);
    //     if($is_method === 'receptionist'){
          
    //         return response()->json(['status'=>'success', 'booking_id'=> $booking->getId(), 'id'=>$booking->id]);
    //     }
    //     $notify[] = ['success', 'Đã nhận thanh toán thành công'];
    //     return back()->withNotify($notify);
    // }

    protected function returnPayment($booking, $receivingAmount)
    {
        $booking->createPaymentLog($receivingAmount, 'RETURNED');
        $booking->createActionHistory('payment_returned');

        $booking->paid_amount -= $receivingAmount;
        $booking->save();

        // $notify[] = ['success', 'Thanh toán đã hoàn tất thành công'];
        // return back()->withNotify($notify);
    }

    protected function deposit($booking, $payableAmount)
    {
        $data = new Deposit();
        $data->user_id = $booking->user_id;
        $data->booking_id = $booking->id;
        $data->admin_id = auth('admin')->id();
        $data->amount = $payableAmount;
        $data->charge = 0;
        $data->final_amount = $payableAmount;
        $data->btc_amount = 0;
        $data->trx = getTrx();
        $data->btc_wallet = "";
        $data->payment_try = 0;
        $data->status = Status::PAYMENT_SUCCESS; // 1
        $data->save();
    }
}
