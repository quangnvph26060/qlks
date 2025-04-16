<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PremiumService;
use App\Models\Product;
use App\Models\RoomServiceProduct;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class PremiumServiceController extends Controller
{
    public function index(Request $request)
    {
        $input         = $request->name;
        $pageTitle     = 'Dịch vụ cao cấp';
        // PremiumService::latest()->paginate(getPaginate());
        $premiumServices = PremiumService::query();
        if (!empty($input)) {
            $premiumServices->where('name', 'like', '%' . $input . '%');
        }
        $premiumServices = $premiumServices->paginate(10);
        return view('admin.hotel.premium_services', compact('pageTitle', 'premiumServices', 'input'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'code' => 'unique:premium_services,code|max:6',
            'name'       => 'required|string|max:255|unique:premium_services,name,' . $id,
            'cost'       => 'required|integer|gt:0'
        ]);

        if ($id) {
            $premiumService         = PremiumService::findOrFail($id);
            $notification          = 'Dịch vụ đã được cập nhật thành công';
        } else {
            $premiumService = new PremiumService();
            $notification  = 'Dịch vụ đã được thêm thành công';
        }

        $premiumService->code = $request->code;
        $premiumService->name = $request->name;
        $premiumService->cost = $request->cost;

        $premiumService->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return PremiumService::changeStatus($id);
    }
    public function delete($id)
    {
        PremiumService::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa dịch vụ thành công',
        ]);
    }
    public function getAllService(Request $request)
    {
        $input = $request->input('search'); // ví dụ lấy input từ request

        $premiumServices = PremiumService::query()
            ->active()
            ->where('unit_code', unitCode());

        if (!empty($input)) {
            $premiumServices->where('name', 'like', '%' . $input . '%');
        }

        $products = Product::query()
            ->where('unit_code', unitCode());

        if (!empty($input)) {
            $products->where('name', 'like', '%' . $input . '%');
        }

        $premiumServices = $premiumServices->select('id', 'name', 'cost as price')->get()->map(function ($item) {
            $item->type = 'premium_service';
            return $item;
        });

        $products = $products->select('id', 'name', 'selling_price as price')->get()->map(function ($item) {
            $item->type = 'product';
            return $item;
        });
        $data = array_values(array_merge(
            $premiumServices->toArray(),
            $products->toArray()
        ));


        return response()->json(['status' => 'success', 'data' => $data]);
    }
    public function storeServices(Request $request)
    {
        $checkInId   = $request->data['checkin_id'];
        $roomCode    = $request->data['room_code'];
        $bookingDate = now();
        $creator     = authAdmin()->id;
    
        $products  = $request->data['product'] ?? [];
        $services  = $request->data['premium_service'] ?? [];
    
        foreach ($products as $product) {
            $existing = RoomServiceProduct::where('check_in_id', $checkInId)
                ->where('room_code', $roomCode)
                ->where('product_id', $product['id'])
                ->whereNull('service_id')
                ->first();
    
            if ($existing) {
                $existing->quantity      += $product['quantity'];
                $existing->price          = $product['price'];
                $existing->total_payment  = $existing->quantity * $existing->price;
                $existing->updated_at     = now();
                $existing->save();
            } else {
                RoomServiceProduct::create([
                    'product_id'    => $product['id'],
                    'service_id'    => null,
                    'check_in_id'   => $checkInId,
                    'room_code'     => $roomCode,
                    'booking_date'  => $bookingDate,
                    'quantity'      => $product['quantity'],
                    'price'         => $product['price'],
                    'total_payment' => $product['price'] * $product['quantity'],
                    'creator'       => $creator,
                    'unit_code'     => unitCode(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    
        foreach ($services as $service) {
            $existing = RoomServiceProduct::where('check_in_id', $checkInId)
                ->where('room_code', $roomCode)
                ->where('service_id', $service['id'])
                ->whereNull('product_id')
                ->first();
    
            if ($existing) {
                $existing->quantity      += $service['quantity'];
                $existing->price          = $service['price'];
                $existing->total_payment  = $existing->quantity * $existing->price;
                $existing->updated_at     = now();
                $existing->save();
            } else {
                RoomServiceProduct::create([
                    'product_id'    => null,
                    'service_id'    => $service['id'],
                    'check_in_id'   => $checkInId,
                    'room_code'     => $roomCode,
                    'booking_date'  => $bookingDate,
                    'quantity'      => $service['quantity'],
                    'price'         => $service['price'],
                    'total_payment' => $service['price'] * $service['quantity'],
                    'creator'       => $creator,
                    'unit_code'     => unitCode(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    
        return response()->json([
            'status'  => 'success',
            'message' => 'Thêm dịch vụ thành công!',
        ]);
    }
    
}
