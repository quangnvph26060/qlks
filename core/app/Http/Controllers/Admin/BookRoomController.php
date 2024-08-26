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

class BookRoomController extends Controller {
    use BookingActions;

    public function room() {
        $pageTitle = 'Book Room';
        $roomTypes = RoomType::active()->get(['id', 'name']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.booking.book', compact('pageTitle', 'roomTypes', 'countries'));
    }

    function searchRoom(Request $request) {

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

    public function book(Request $request) {
        $validator = Validator::make($request->all(), [
            'room_type_id'    => 'required|integer|gt:0',
            'guest_type'      => 'required|in:1,0',
            'guest_name'      => 'nullable|required_if:guest_type,0',
            'email'           => 'required|email',
            'mobile'          => 'nullable|required_if:guest_type,0|regex:/^([0-9]*)$/',
            'address'         => 'nullable|required_if:guest_type,0|string',
            'room'            => 'required|array',
            'paid_amount'     => 'nullable|numeric|gte:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $guest = [];

        if ($request->guest_type == 1) {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['error' => 'No registered guest found with this email']);
            }
        } else {
            $guest['name'] = $request->guest_name;
            $guest['email'] = $request->email;
            $guest['mobile'] = $request->mobile;
            $guest['address'] = $request->address;
        }

        $bookedRoomData = [];
        $totalFare      = 0;
        $tax            = gs('tax');

        foreach ($request->room as $room) {
            $data      = [];
            $roomId    = explode('-', $room)[0];
            $bookedFor = explode('-', $room)[1];
            $isBooked  = BookedRoom::where('room_id', $roomId)->where('booked_for', $bookedFor)->exists();

            if ($isBooked) {
                return response()->json(['error' => 'Room has been booked']);
            }

            $room = Room::with('roomType')->find($roomId);

            if ($request->room_type_id != @$room->roomType->id) {
                return response()->json(['error' => 'Invalid room type selected']);
            }

            $data['booking_id']       = 0;
            $data['room_type_id']  = $room->room_type_id;
            $data['room_id']          = $room->id;
            $data['booked_for']       = Carbon::parse($bookedFor)->format('Y-m-d');
            $data['fare']             = $room->roomType->fare;
            $data['tax_charge']       = $room->roomType->fare * $tax / 100;
            $data['cancellation_fee'] = $room->roomType->cancellation_fee;
            $data['status']           = Status::ROOM_ACTIVE;
            $data['created_at']       = now();
            $data['updated_at']       = now();

            $bookedRoomData[] = $data;

            $totalFare += $room->roomType->fare;
        }


        $taxCharge = $totalFare * $tax / 100;

        if ($request->paid_amount && $request->paid_amount > $totalFare + $taxCharge) {
            return response()->json(['error' => 'Paying amount can\'t be greater than total amount']);
        }

        $booking                 = new Booking();
        $booking->booking_number = getTrx();
        $booking->user_id        = @$user->id ?? 0;
        $booking->guest_details  = $guest;
        $booking->tax_charge     = $taxCharge;
        $booking->booking_fare   = $totalFare;
        $booking->paid_amount    = $request->paid_amount ?? 0;
        $booking->status         = Status::BOOKING_ACTIVE;
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
        $booking->check_out = Carbon::parse($checkout)->addDay()->toDateString();
        $booking->save();

        return response()->json(['success' => 'Room booked successfully']);
    }
}
