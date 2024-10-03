<?php

namespace App\Http\Controllers\User;

use App\Models\Booking;
use App\Constants\Status;
use App\Models\BookingRequest;
use App\Http\Controllers\Controller;
use App\Models\BookingRequestItem;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{

    public function allBookings()
    {
        $pageTitle = 'Booking History';
        $bookings  = Booking::where('user_id', Auth::id())->orderBy('id', 'DESC')->orderBy('check_out', 'asc')->paginate(getPaginate());
        return view('Template::user.booking.all', compact('pageTitle', 'bookings'));
    }

    public function bookingRequestList()
    {
        $pageTitle = "Tất cả yêu cầu đặt chỗ";
        $bookingRequests = BookingRequest::where('user_id', Auth::id())->with('bookingItems.room')->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('Template::user.booking.request', compact('bookingRequests', 'pageTitle'));
    }


    public function cancelBookingRequest($id)
    {
        BookingRequest::initial()->where('user_id', Auth::id())->where('id', $id)->delete();

        $notify[] = ['success', 'Booking request canceled successfully'];
        return back()->withNotify($notify);
    }

    public function cancelBookingRequestItem($id)
    {
        $id = request()->id;
        $bookingRequestItem = BookingRequestItem::with('bookingRequest')->where('id', $id)->first();

        // Chuyển đổi check_in và check_out thành đối tượng Carbon
        $checkInDate = \Carbon\Carbon::parse($bookingRequestItem->bookingRequest->check_in);
        $checkOutDate = \Carbon\Carbon::parse($bookingRequestItem->bookingRequest->check_out);

        // Tính số ngày giữa check_in và check_out
        $today = $checkInDate->diffInDays($checkOutDate);

        $total = $bookingRequestItem->bookingRequest->total_amount - (($bookingRequestItem->unit_fare + $bookingRequestItem->tax_charge) *  $today);

        $bookingRequestItem->update(['status' => Status::BOOKING_REQUEST_CANCELED]);
        $bookingRequestItem->bookingRequest->update(['total_amount' => $total]);

        $result = $bookingRequestItem->bookingRequest->where('status', Status::BOOKING_REQUEST_APPROVED)->count();

        if ($result == 0) {
            $bookingRequestItem->bookingRequest->delete();
        }

        $notify[] = ['success', 'Hủy phòng thành công.'];
        return back()->withNotify($notify);
    }


    public function bookingDetails($id)
    {
        $user = Auth::user();
        $booking = Booking::where('user_id', $user->id)->with([
            'bookedRooms',
            'bookedRooms.room:id,room_type_id,room_number',
            'bookedRooms.room.roomType:id,name',
            'usedPremiumService.room',
            'usedPremiumService.premiumService',
            'payments'
        ])->findOrFail($id);

        $pageTitle = 'Chi tiết đặt phòng';

        return view('Template::user.booking.details', compact('pageTitle', 'booking'));
    }

    public function payment($id)
    {
        $booking = Booking::with(['bookedRooms.roomType.images', 'bookedRooms.room', 'bookedRooms.booking.user'])->findOrFail($id);
        session()->put('amount', getAmount($booking->total_amount - $booking->paid_amount));
        session()->put('booking_id', $booking->id);
        return to_route('user.deposit.index', $id)->with('booking', $booking);
    }
}
