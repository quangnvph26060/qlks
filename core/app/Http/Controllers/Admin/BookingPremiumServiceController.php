<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PremiumService;
use App\Models\UsedPremiumService;
use App\Models\Booking;
use App\Models\BookedRoom;
use App\Models\BookingActionHistory;
use App\Models\Product;
use App\Models\UserdProductRoom;

class BookingPremiumServiceController extends Controller
{
    public function all()
    {
        $pageTitle     = 'Thêm dịch vụ cao cấp';
        $premiumService = PremiumService::active()->get();
        $services      = UsedPremiumService::searchable(['premiumService:name', 'room:room_number'])->with('premiumService', 'room', 'admin')->paginate(getPaginate());
        return view('admin.booking.service_details', compact('pageTitle', 'services'));
    }

    public function addNew()
    {
        $pageTitle     = 'Thêm dịch vụ cao cấp';
        $premiumServices = PremiumService::active()->get();
        return view('admin.premium_service.add', compact('pageTitle', 'premiumServices'));
    }

    public function addService(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_number'  => 'required',
            'service_date' => 'required|date_format:Y-m-d|before:tomorrow',
            'services'     => 'required|array',
            'services.*'   => 'required|exists:premium_services,id',
            'qty'          => 'required|array',
            'qty.*'        => 'integer|gt:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        // thêm dịch vụ cao cấp
        $serviceRoom = BookedRoom::whereHas('room', function ($q) use ($request) {
            $q->where('room_number', $request->room_number);
        })->whereDate('booked_for', $request->service_date)->where('status', Status::ROOM_ACTIVE)->first();

        if (!$serviceRoom) {
            return response()->json(['error' => [ 'Phòng ' . $request->room_number . ' số phòng chưa được đặt cho ' . showDateTime($request->service_date, 'd M, Y')]]);
        }

        $booking = Booking::find($serviceRoom->booking_id);
        $totalAmount = 0;
        $data = [];

        foreach ($request->services as $key => $service) {
            $serviceDetails                   = PremiumService::find($service);
            $data[$key]['booking_id']         = $booking->id;
            $data[$key]['premium_service_id'] = $service;
            $data[$key]['room_id']            = $serviceRoom->room_id;
            $data[$key]['booked_room_id']     = $serviceRoom->id;
            $data[$key]['qty']                = $request->qty[$key];
            $data[$key]['unit_price']         = $serviceDetails->cost;
            $data[$key]['total_amount']       = $request->qty[$key] * $serviceDetails->cost;
            $data[$key]['service_date']       = $request->service_date;
            $data[$key]['admin_id']           = auth('admin')->id();
            $data[$key]['created_at']         = now();
            $data[$key]['updated_at']         = now();

            $totalAmount += $request->qty[$key] * $serviceDetails->cost;
        }

        $usedPremiumService  = new UsedPremiumService();
        $usedPremiumService->insert($data);
        $booking->service_cost += $totalAmount;
        $booking->save();

        $action             = new BookingActionHistory();
        $action->booking_id = $serviceRoom->booking_id;
        $action->remark     = 'added_extra_service';
        $action->admin_id   = authAdmin()->id;
        $action->save();    

        $data = [
            'booking_id' => $booking->id,
            'booked_room_id' => $booking->getId()
        ];

        return response()->json(['success' => 'Dịch vụ cao cấp được thêm thành công','data'=>$data]);
    }
    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_number'  => 'required',
            'product_date' => 'required|date_format:Y-m-d|before:tomorrow',
            'product'      => 'required|array',
            'product.*'    => 'required|exists:products,id',
            'qty'          => 'required|array',
            'qty.*'        => 'integer|gt:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        // thêm dịch vụ cao cấp
        $productRoom = BookedRoom::whereHas('room', function ($q) use ($request) {
            $q->where('room_number', $request->room_number);
        })->whereDate('booked_for', $request->product_date)->where('status', Status::ROOM_ACTIVE)->first();

        if (!$productRoom) {
            return response()->json(['error' => [ 'Phòng ' . $request->room_number . ' số phòng chưa được đặt cho ' . showDateTime($request->product_date, 'd M, Y')]]);
        }

        $booking = Booking::find($productRoom->booking_id);
        $totalAmount = 0;
        $data = [];

        foreach ($request->product as $key => $item) {
            $productDetails                   = Product::find($item);
            $data[$key]['booking_id']         = $booking->id;
            $data[$key]['product_id']         = $item;
            $data[$key]['room_id']            = $productRoom->room_id;
            $data[$key]['booked_room_id']     = $productRoom->id;
            $data[$key]['qty']                = $request->qty[$key];
            $data[$key]['unit_price']         = $productDetails->selling_price;
            $data[$key]['total_amount']       = $request->qty[$key] * $productDetails->selling_price;
            $data[$key]['product_date']       = $request->product_date;
            $data[$key]['admin_id']           = auth('admin')->id();
            $data[$key]['created_at']         = now();
            $data[$key]['updated_at']         = now();

            $totalAmount += $request->qty[$key] * $productDetails->selling_price;
        }

        $productRooms  = new UserdProductRoom();
        $productRooms->insert($data);
        $booking->product_cost += $totalAmount;
        $booking->save();

        $action             = new BookingActionHistory();
        $action->booking_id = $productRoom->booking_id;
        $action->remark     = 'added_extra_product';
        $action->admin_id   = authAdmin()->id;
        $action->save();
        $data = [
            'booking_id' => $booking->id,
            'booked_room_id' => $booking->getId()
        ];

        return response()->json(['success' => 'Sản phẩm được thêm thành công','data' => $data]);
    }
    public function delete($id)
    {
        $service      = UsedPremiumService::findOrFail($id);
        $booking      = $service->booking;

        $booking->service_cost -=  $service->total_amount;
        $booking->save();

        $action             = new BookingActionHistory();
        $action->booking_id = $booking->id;
        $action->remark     = 'deleted_extra_service';
        $action->admin_id   = authAdmin()->id;
        $action->save();

        $service->delete();

        $notify[] = ['success', 'Dịch vụ cao cấp đã xóa thành công'];
        return back()->withNotify($notify);
    }
}
