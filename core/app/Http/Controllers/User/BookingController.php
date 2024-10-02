<?php

namespace App\Http\Controllers\User;

use App\Models\Booking;
use App\Models\BookingRequest;
use App\Http\Controllers\Controller;
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
        $bookingRequests = BookingRequest::where('user_id', Auth::id())->with('bookingItems')->orderBy('id', 'DESC')->paginate(getPaginate());
        // dd($bookingRequests);
        return view('Template::user.booking.request', compact('bookingRequests', 'pageTitle'));
    }


    public function cancelBookingRequest($id)
    {
        BookingRequest::initial()->where('user_id', Auth::id())->where('id', $id)->delete();

        $notify[] = ['success', 'Booking request canceled successfully'];
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
        $booking = Booking::with(['bookedRooms.roomType.images', 'bookedRooms.room', 'bookedRooms.booking.user' ])->findOrFail($id);
        session()->put('amount', getAmount($booking->total_amount - $booking->paid_amount));
        session()->put('booking_id', $booking->id);
        return to_route('user.deposit.index',$id)->with('booking', $booking);
    }
}
