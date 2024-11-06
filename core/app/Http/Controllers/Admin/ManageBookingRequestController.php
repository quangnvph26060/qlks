<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Booking;
use App\Constants\Status;
use App\Events\EventRegisterUser;
use App\Models\BookedRoom;
use Illuminate\Http\Request;
use App\Models\BookingRequest;
use App\Traits\BookingActions;
use App\Models\BookingRequestItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Events\RoomCancellationEvent;
use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Event;
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
        $bookingRequest = BookingRequest::with('bookingItems.room.roomType')->findOrFail($id);


        if ($bookingRequest->status) {
            $notify[] = ['error', 'Yêu cầu đặt phòng này đã được chấp thuận'];
            return to_route('admin.request.booking.all')->withNotify($notify);
        }
        $pageTitle = "Chỉ định phòng";

        $roomTypes = $bookingRequest->bookingItems->map(function ($item) {
            return $item->room->roomType->name; // Giả sử 'name' là thuộc tính chứa tên loại phòng
        })->unique();

        $request->merge([
            'room_id'     => $bookingRequest->bookingItems->first()->room_id,
            'rooms'         => $bookingRequest->bookingItems->count(),
            'checkin_date'  => $bookingRequest->check_in,
            'checkout_date' => $bookingRequest->check_out,
            'total_amount'     => $bookingRequest->total_amount
        ]);

        $roomTypesName = implode(', ', $roomTypes->toArray());

        $response =  $this->checkRoomAvailability($request, $bookingRequest);

        return view('admin.booking.request_approve', compact('pageTitle', 'bookingRequest', 'roomTypesName', 'response', 'roomTypes'));
    }

    private function checkRoomAvailability(Request $request, $bookingRequest)
    {
        $checkIn = Carbon::parse($request->checkin_date)->format('Y-m-d');
        $checkOut = Carbon::parse($request->checkout_date)->format('Y-m-d');

        // Duyệt qua các phòng trong $bookingRequest
        foreach ($bookingRequest->bookingItems as $room) {
            // Kiểm tra xem phòng có bị đặt trong khoảng thời gian này hay không
            $bookedRoom = BookedRoom::with('booking')
                ->where('room_id', $room->room_id)
                ->where('status', 1)  // Phòng đã được đặt
                ->where(function ($query) use ($checkIn, $checkOut) {
                    // Kiểm tra xem phòng đã đặt trùng với ngày mới
                    $query->whereBetween('booked_for', [$checkIn, $checkOut])
                        // Kiểm tra xem phòng có booking với ngày checkout sau ngày checkin yêu cầu
                        ->orWhereHas('booking', function ($subQuery) use ($checkIn) {
                            $subQuery->where('check_out', '>', $checkIn);
                        });
                })
                ->first(); // Chỉ cần lấy một kết quả

            // Kiểm tra kết quả, nếu phòng đã được đặt thì set is_used = true, ngược lại là false
            $room->is_used = $bookedRoom ? true : false;
        }

        // Trả về booking request đã được cập nhật
        return $bookingRequest;
    }



    public function assignRoom(Request $request)
    {
        try {
            DB::beginTransaction();

            // Bước 1: Xác nhận thông tin của booking request
            $bookingRequest = BookingRequest::with('bookingItems.room')
                ->find($request->booking_request_id);

            if (!$bookingRequest) {
                return to_route('admin.request.booking.all')
                    ->withErrors('Không tìm thấy yêu cầu đặt phòng');
            }

            // Bước 2: Tạo một booking mới
            $booking = Booking::create([
                'booking_number' => uniqid(),
                'user_id' => $bookingRequest->user_id,
                'check_in' => $bookingRequest->check_in,
                'check_out' => $bookingRequest->check_out,
                'guest_details' => $request->guest_details,
                'tax_charge' => 0,
                'booking_fare' => 0,
                'status' => Status::BOOKING_ACTIVE,
            ]);

            $request->merge(['booking_id' => $booking->id]);

            // Bước 3: Kiểm tra và hủy các phòng trùng
            $availableRooms = $this->checkAndCancelRooms($request);
           
            if (empty($availableRooms)) {
               
                $result =   BookingRequest::find($request->booking_request_id);
                if (!$result) {
                    $notify[] = ['error', 'Không đặt phòng thành công'];
                    return back()->withNotify($notify);
                    DB::rollBack();
                }
                $result->delete();
                Booking::where('id', $booking->id)->delete(); 
                DB::commit();
                $notify[] = ['success', 'Tất cả các phòng yêu cầu đã bị trùng.'];
                return redirect()->route('admin.request.booking.all')->withNotify($notify);
            }

            // Tính toán tổng chi phí
            $totalAmount = collect($availableRooms)->sum(function ($room) use ($bookingRequest) {
                return ($room['fare'] + $room['tax_charge']) *
                    Carbon::parse($bookingRequest->check_in)
                    ->diffInDays(Carbon::parse($bookingRequest->check_out));
            });

            // Cập nhật giá trị cho booking
            $booking->update([
                'booking_fare' => $totalAmount,
                'tax_charge' => $totalAmount * 0.1,
            ]);
            // Bước 5: Thêm các phòng không bị trùng vào bảng BookedRoom
            $bookedRoom = BookedRoom::insert($availableRooms);
            if (!$bookedRoom) {
                $notify[] = ['error', 'Không đặt phòng thành công'];
                return back()->withNotify($notify);
                DB::rollBack();
            }

            BookingRequestItem::where('booking_request_id', $request->booking_request_id)->delete();

            BookingRequest::find($request->booking_request_id)->delete();
            DB::commit();
            $notify[] = ['success', 'Đặt phòng thành công'];
            return redirect()->route('admin.request.booking.all')->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Đã có lỗi xảy ra, vui lòng thử lại sau!');
        }
    }

    /**
     * Hàm kiểm tra và hủy các phòng trùng
     */
    // [2024-10-07 17:07:49] local.INFO: array (
    //     'roomCanAssign' => 
    //     array (
    //       0 => '31',
    //     ),
    //     'booking_request_id' => '25',
    //     '_token' => 'yeqqYuI73RgiVydf0VG0NP5x0WzOgxuZYKolLax3',
    //   )
    private function checkAndCancelRooms($request)
    {
        $data = $request->all();
        $cancelledRooms = $data['roomCanNotAssign'] ?? [];
        $availableRooms = $data['roomCanAssign'] ?? [];
        \Log::info($data);
        // [2024-10-08 08:41:26] local.INFO: array (
        //     'roomCanNotAssign' => 
        //     array (
        //       0 => '28',
        //     ),
        //     'booking_request_id' => '35',
        //     '_token' => 'kMvlKH9zQ1NzzVJJSdmpDajyTd5VU9RaMncUg01t',
        //     'booking_id' => 199,
        //   )  
          
        if (!empty($cancelledRooms)) {
            BookingRequestItem::where('booking_request_id', $data['booking_request_id'])
                ->whereIn('room_id', $cancelledRooms)
                ->update(['status' => Status::ROOM_CANCELED]);

            try {
                $this->sendCancellationNotifications($cancelledRooms, $data['booking_request_id']);
            } catch (\Exception $e) {
                \Log::info($e->getMessage());
            }
        }

        if (!empty($availableRooms)) {
            return BookingRequestItem::with('room', 'bookingRequest')
                ->where('booking_request_id', $data['booking_request_id'])
                ->whereIn('room_id', $availableRooms)
                ->get()
                ->map(function ($item) use ($data) {
                    return [
                        'booking_id' => $data['booking_id'],
                        'room_id' => $item->room_id,
                        'booked_for' => $item->bookingRequest->check_in,
                        'fare' => $item->unit_fare,
                        'tax_charge' => $item->tax_charge,
                        'cancellation_fee' => $item->room->cancellation_fee,
                        'status' => Status::ROOM_ACTIVE,
                    ];
                })->toArray();
        }

        return [];
    }



    /**
     * Hàm gửi email thông báo về các phòng bị hủy
     */
    private function sendCancellationNotifications($cancelledRooms, $bookingRequestId)
    {
        $bookingRequest = BookingRequest::with('bookingItems.room', 'user')->find($bookingRequestId);
        // Dispatch sự kiện với đối tượng BookingRequest
        $data = ['type'=>'EMAIL_CANCEL_BOOKED_ROOM',
        'bookingRequest' => $bookingRequest,
        'cancelledRooms'=>$cancelledRooms
            ];
        event(new RoomCancellationEvent($data));
       
    
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
