<?php

namespace App\Traits;

use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

trait BookingActions {
    private function getRooms(Request $request) {
        $checkIn = Carbon::parse($request->checkin_date);
        $checkOut = $request->checkout_date ? Carbon::parse($request->checkout_date) : $checkIn;
        // kiểm tra rooms cb đặt đã được đặt trong khoảng thời gian đó chưa 
        $rooms = Room::active()
            ->where('room_type_id', $request->room_type)
            ->with([
                'booked' => function ($q) {
                    $q->active();
                },
                'roomType' => function ($q) {
                    $q->select('id', 'name', 'fare');
                },
               
            ],'roomPricesActive')
            ->get();
            \Log::info($rooms);
        if (count($rooms) < $request->rooms) {
            return ['error' => ['Số phòng yêu cầu không có sẵn cho ngày đã chọn']]; //The requested number of rooms is not available for the selected date
        }

        $numberOfRooms = $request->rooms;
        $requestUnitFare = $request->unit_fare;

        return view('partials.rooms', compact('checkIn', 'checkOut', 'rooms', 'numberOfRooms', 'requestUnitFare'))->render();
    }
}
