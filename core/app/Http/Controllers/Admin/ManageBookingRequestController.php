<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingRequest;
use App\Models\Room;
use App\Models\Booking;
use App\Models\BookedRoom;
use App\Traits\BookingActions;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ManageBookingRequestController extends Controller
{
    use BookingActions;

    public function index()
    {
        $pageTitle = 'Tất cả yêu cầu đặt chỗ';
        $bookingRequests = $this->bookingRequestData('initial'); // Lấy đối tượng phân trang
        return view('admin.booking.request_list', compact('pageTitle', 'bookingRequests'));
    }

    public function canceledBookings()
    {
        $pageTitle       = 'Yêu cầu đặt phòng đã hủy';
        $bookingRequests = $this->bookingRequestData('canceled');
        return view('admin.booking.canceled_requests', compact('pageTitle', 'bookingRequests'));
    }

    public function cancel($id)
    {
        $bookingRequest = BookingRequest::initial()->findOrFail($id);
        $bookingRequest->status = Status::BOOKING_REQUEST_CANCELED;
        $bookingRequest->save();

        notify($bookingRequest->user, 'BOOKING_REQUEST_CANCELED', [
            'room_type'       => $bookingRequest->roomType->name,
            'number_of_rooms' => $bookingRequest->number_of_rooms,
            'check_in'        => showDateTime($bookingRequest->check_in, 'd M, Y'),
            'check_out'       => showDateTime($bookingRequest->check_out, 'd M, Y')
        ]);

        $notify[] = ['success', 'Yêu cầu đặt phòng đã hủy thành công'];
        return back()->with($notify);
    }

    public function approve(Request $request, $id)
    {
        $bookingRequest = BookingRequest::findOrFail($id);
        if ($bookingRequest->status) {
            $notify[] = ['error', 'Yêu cầu đặt phòng này đã được chấp thuận'];
            return to_route('admin.request.booking.all')->withNotify($notify);
        }
        $pageTitle = "Chỉ định phòng";

        $request->merge([
            'room_type'     => $bookingRequest->room_id,
            'rooms'         => $bookingRequest->number_of_rooms,
            'checkin_date'  => $bookingRequest->check_in,
            'checkout_date' => $bookingRequest->check_out,
            'unit_fare'     => $bookingRequest->unit_fare
        ]);

        // dd($request->all());

        $view =  $this->getRooms($request); // getRooms function's definition is in App/Traits/BookingActions

        return view('admin.booking.request_approve', compact('pageTitle', 'view', 'bookingRequest'));
    }

    public function assignRoom(Request $request)
    {
        $request->validate([
            'booking_request_id' => 'required|exists:booking_requests,id',
            'room'               => 'required|array',
            'paid_amount'        => 'nullable|numeric|gt:0'
        ]);

        $bookingRequest = BookingRequest::with('user', 'roomType')->findOrFail($request->booking_request_id);
        $this->bookingValidation($request, $bookingRequest);

        $user           = $bookingRequest->user;
        $checkInDate    = Carbon::parse($bookingRequest->check_in);
        $checkOutDate   = Carbon::parse($bookingRequest->check_out);
        $bookingFare    = $bookingRequest->unit_fare * $bookingRequest->number_of_rooms * $checkInDate->diffInDays($checkOutDate);

        $booking                   = new Booking();
        $booking->booking_number   = getTrx();
        $booking->user_id          = $user->id;
        $booking->check_in         = $bookingRequest->check_in;
        $booking->check_out        = $bookingRequest->check_out;
        $booking->tax_charge       = $bookingRequest->tax_charge;
        $booking->booking_fare     = $bookingFare;
        $booking->paid_amount      = $request->paid_amount ?? 0;
        $booking->save();

        $booking->createActionHistory('approve_booking_request');

        if ($request->paid_amount > 0) {
            $booking->createPaymentLog($request->paid_amount, 'RECEIVED');
        }

        $roomIds      = [];
        $bookingRoom  = [];

        foreach ($request->room as $key => $room) {
            $roomId       = explode('-', $room)[0];
            $bookedFor    = explode('-', $room)[1];
            $bookedFor    = Carbon::parse($bookedFor)->format('Y-m-d');
            $room         = Room::with('roomType')->findOrFail($roomId);

            $bookingRoom[$key]['booking_id']    = $booking->id;
            $bookingRoom[$key]['room_type_id']  = $room->room_type_id;
            $bookingRoom[$key]['room_id']       = $room->id;
            $bookingRoom[$key]['booked_for']    = $bookedFor;
            $bookingRoom[$key]['fare']          = $room->roomType->fare;
            $bookingRoom[$key]['tax_charge']    = $booking->tax_charge / count($request->room);
            $bookingRoom[$key]['cancellation_fee'] = $room->roomType->cancellation_fee;
            $bookingRoom[$key]['status']        = Status::ROOM_ACTIVE;
            $bookingRoom[$key]['created_at']    = now();
            $bookingRoom[$key]['updated_at']    = now();

            array_push($roomIds, $room->id);
        }

        BookedRoom::insert($bookingRoom);

        $booking->status = Status::BOOKING_ACTIVE;
        $booking->save();

        $bookingRequest->delete();

        $roomNumbers = Room::whereIn('id', $roomIds)->pluck('room_number')->toArray();
        $rooms       = implode(" , ", $roomNumbers);

        notify($user, 'ROOM_BOOKED', [
            'booking_number' => $booking->booking_number,
            'amount'         => showAmount($booking->total_amount, currencyFormat: false),
            'paid_amount'    => showAmount($booking->paid_amount, currencyFormat: false),
            'rooms'          => $rooms,
            'check_in'       => Carbon::parse($booking->check_in)->format('d M, Y'),
            'check_out'      => Carbon::parse($booking->check_out)->format('d M, Y')
        ]);

        $notify[] = ['success', 'Booking Request approved successfully'];
        return to_route('admin.request.booking.all')->withNotify($notify);
    }

    private function bookingValidation($request, $bookingRequest)
    {
        $bookingCount = [];

        foreach ($request->room as $room) {
            $bookedFor = explode('-', $room)[1];
            $bookedFor = Carbon::parse($bookedFor)->format('Y-m-d');
            $bookingCount[$bookedFor] = @$bookingCount[$bookedFor] + 1;
        }

        $dates = array_keys($bookingCount);
        sort($bookingCount);

        if ($dates[0] != $bookingRequest->check_in) {
            throw ValidationException::withMessages(['error' => 'Check in date must be same as booking request checkin date']);
        }

        if (end($dates) != Carbon::parse($bookingRequest->check_out)->subDay()->format('Y-m-d')) {
            throw ValidationException::withMessages(['error' => 'Check out date must be same as booking request checkout date']);
        }

        if ($bookingCount[0] < $bookingRequest->number_of_rooms) {
            throw ValidationException::withMessages(['error' => 'You can\'t booked less than of request rooms!']);
        }

        if (end($bookingCount) > $bookingRequest->number_of_rooms) {
            throw ValidationException::withMessages(['error' => 'You can\'t booked greater than of request rooms!']);
        }
    }

    // protected function bookingRequestData($scope)
    // {
    //     $query = BookingRequest::$scope()->searchable(['user:username,email'])->with('user')->orderBy('id', 'DESC')->paginate(getPaginate());
    //     return $query;
    // }

    protected function bookingRequestData($scope)
    {
        $query = BookingRequest::$scope()
            ->searchable(['user:username,email'])
            ->with('user', 'bookingItems.room.roomType')
            ->orderBy('id', 'DESC')
            ->paginate(getPaginate()); // Thay vì trả về mảng, giữ lại đối tượng phân trang

        // Thêm thông tin phòng vào đối tượng phân trang
        foreach ($query as $bookingRequest) {
            $roomTypeCounts = [];

            foreach ($bookingRequest->bookingItems as $bookingItem) {
                $roomTypeId = $bookingItem->room->room_type_id;
                $roomTypeName = $bookingItem->room->roomType->name;

                if (isset($roomTypeCounts[$roomTypeId])) {
                    $roomTypeCounts[$roomTypeId]['count']++;
                } else {
                    $roomTypeCounts[$roomTypeId] = [
                        'name' => $roomTypeName,
                        'count' => 1,
                    ];
                }
            }

            // Tạo chuỗi thông tin cho từng yêu cầu
            $info = [];
            foreach ($roomTypeCounts as $roomTypeData) {
                $info[] = $roomTypeData['count'] . " " . $roomTypeData['name'];
            }

            // Lưu thông tin vào thuộc tính `roomInfo` của bookingRequest
            $bookingRequest->roomInfo = implode(" | ", $info); // Thêm thông tin phòng vào model
        }

        return $query; // Trả về đối tượng phân trang
    }
}
