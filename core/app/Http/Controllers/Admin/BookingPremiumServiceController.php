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
            'qty.*'        => 'integer|integer'
        ]);
        $validator->after(function ($validator) use ($request) {
            // Kiểm tra nếu ít nhất một phần tử trong mảng `qty` > 0
            if (!collect($request->qty)->contains(fn($value) => $value > 0)) {
                $validator->errors()->add('qty', 'Ít nhất một số lượng phải lớn hơn 0.');
            }
        });

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
        // $totalAmount = 0;
        // $data = [];

        // foreach ($request->services as $key => $service) {
        //     $serviceDetails                   = PremiumService::find($service);
        //     $data[$key]['booking_id']         = $booking->id;
        //     $data[$key]['premium_service_id'] = $service;
        //     $data[$key]['room_id']            = $serviceRoom->room_id;
        //     $data[$key]['booked_room_id']     = $serviceRoom->id;
        //     $data[$key]['qty']                = $request->qty[$key];
        //     $data[$key]['unit_price']         = $serviceDetails->cost;
        //     $data[$key]['total_amount']       = $request->qty[$key] * $serviceDetails->cost;
        //     $data[$key]['service_date']       = $request->service_date;
        //     $data[$key]['admin_id']           = auth('admin')->id();
        //     $data[$key]['created_at']         = now();
        //     $data[$key]['updated_at']         = now();

        //     $totalAmount += $request->qty[$key] * $serviceDetails->cost;
        // }

        // $usedPremiumService  = new UsedPremiumService();
        // $usedPremiumService->insert($data);
        // $booking->service_cost += $totalAmount;
        // $booking->save();

        // $action             = new BookingActionHistory();
        // $action->booking_id = $serviceRoom->booking_id;
        // $action->remark     = 'added_extra_service';
        // $action->admin_id   = authAdmin()->id;
        // $action->save();
        $data = [];
        $totalServiceAmount = 0;

        foreach ($request->services as $key => $service) {
            $serviceDetails = PremiumService::find($service);
            if($request->qty[$key] > 0){
                $newTotalAmount = $request->qty[$key] * $serviceDetails->cost;


                $existingService = UsedPremiumService::where('booking_id', $booking->id)
                    ->where('premium_service_id', $service)
                    ->whereDate('service_date', $request->service_date)
                    ->first();

                if ($existingService) {
                    $existingService->qty = $request->qty[$key];
                    $existingService->total_amount = $newTotalAmount;
                    $existingService->updated_at = now();
                    $existingService->save();

                    $totalServiceAmount += $newTotalAmount;
                } else {
                    // Insert new record
                    $data[] = [
                        'booking_id'         => $booking->id,
                        'premium_service_id' => $service,
                        'room_id'            => $serviceRoom->room_id,
                        'booked_room_id'     => $serviceRoom->id,
                        'qty'                => $request->qty[$key],
                        'unit_price'         => $serviceDetails->cost,
                        'total_amount'       => $newTotalAmount,
                        'service_date'       => $request->service_date,
                        'admin_id'           => auth('admin')->id(),
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ];

                    $totalServiceAmount += $newTotalAmount; // Add new total amount
                }
            }else{
                $existingService = UsedPremiumService::where('booking_id', $booking->id)
                ->where('premium_service_id', $service)
                ->whereDate('service_date', $request->service_date)
                ->first();
                if ($existingService) {
                    $existingService->delete();
                }
            }
        }

        // Insert all new records at once if there are any
        if (!empty($data)) {
            UsedPremiumService::insert($data);
        }
        $sum = UsedPremiumService::where('booking_id', $booking->id)->sum('total_amount');
        // Update the booking service cost
        $booking->service_cost = $sum;
        $booking->save();

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
            'qty.*'        => 'integer|integer'
        ]);
        $validator->after(function ($validator) use ($request) {
            // Kiểm tra nếu ít nhất một phần tử trong mảng `qty` > 0
            if (!collect($request->qty)->contains(fn($value) => $value > 0)) {
                $validator->errors()->add('qty', 'Ít nhất một số lượng phải lớn hơn 0.');
            }
        });
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
        // $totalAmount = 0;
        // $data = [];

        // foreach ($request->product as $key => $item) {
        //     $productDetails                   = Product::find($item);
        //     $data[$key]['booking_id']         = $booking->id;
        //     $data[$key]['product_id']         = $item;
        //     $data[$key]['room_id']            = $productRoom->room_id;
        //     $data[$key]['booked_room_id']     = $productRoom->id;
        //     $data[$key]['qty']                = $request->qty[$key];
        //     $data[$key]['unit_price']         = $productDetails->selling_price;
        //     $data[$key]['total_amount']       = $request->qty[$key] * $productDetails->selling_price;
        //     $data[$key]['product_date']       = $request->product_date;
        //     $data[$key]['admin_id']           = auth('admin')->id();
        //     $data[$key]['created_at']         = now();
        //     $data[$key]['updated_at']         = now();

        //     $totalAmount += $request->qty[$key] * $productDetails->selling_price;
        // }

        // $productRooms  = new UserdProductRoom();
        // $productRooms->insert($data);
        // $booking->product_cost += $totalAmount;
        // $booking->save();
        $data = [];
        $totalProductAmount = 0; // Initialize total for products

        foreach ($request->product as $key => $item) {
            $productDetails = Product::find($item);
            if($request->qty[$key] > 0){
                $newTotalAmount = $request->qty[$key] * $productDetails->selling_price;

                // Check if the product has already been added today
                $existingProduct = UserdProductRoom::where('booking_id', $booking->id)
                    ->where('product_id', $item)
                    ->whereDate('product_date', $request->product_date)
                    ->first();

                if ($existingProduct) {
                    // Update the existing record
                    $existingProduct->qty = $request->qty[$key]; // Update quantity
                    $existingProduct->total_amount = $newTotalAmount; // Set new total amount
                    $existingProduct->updated_at = now();
                    $existingProduct->save(); // Save the updated record

                    // Add the updated total amount directly
                    $totalProductAmount += $newTotalAmount;
                } else {
                    // Insert new record
                    $data[] = [
                        'booking_id'        => $booking->id,
                        'product_id'        => $item,
                        'room_id'           => $productRoom->room_id,
                        'booked_room_id'    => $productRoom->id,
                        'qty'               => $request->qty[$key],
                        'unit_price'        => $productDetails->selling_price,
                        'total_amount'      => $newTotalAmount,
                        'product_date'      => $request->product_date,
                        'admin_id'          => auth('admin')->id(),
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ];

                    $totalProductAmount += $newTotalAmount; // Add new total amount
                }
            }else{
                $existingProduct = UserdProductRoom::where('booking_id', $booking->id)
                    ->where('product_id', $item)
                    ->whereDate('product_date', $request->product_date)
                    ->first();
                if ($existingProduct) {
                    $existingProduct->delete();
                }
            }
        }

        // Insert all new records at once if there are any
        if (!empty($data)) {
            UserdProductRoom::insert($data);
        }
        $sum = UserdProductRoom::where('booking_id', $booking->id)->sum('total_amount');
        // Update the booking product cost
        $booking->product_cost = $sum;
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
