<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PremiumService;
use App\Models\Product;
use App\Models\ReceiptAndPayment;
use App\Models\RoomServiceProduct;
use App\Models\SetupCode;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PremiumServiceController extends Controller
{
    public function index(Request $request)
    {

        $input         = $request->name;
        $pageTitle     = 'Dịch vụ cao cấp';
        $count = PremiumService::count();
        $code = SetupCode::where('menu_name', 'Danh mục dịch vụ cao cấp')->value('code');
        $code = $code ? $code . $count + 1 : '';
        $premiumServices = PremiumService::query();
        if (!empty($input)) {
            $premiumServices->where('name', 'like', '%' . $input . '%');
        }
        $premiumServices = $premiumServices->paginate(10);
        return view('admin.hotel.premium_services', compact('pageTitle', 'premiumServices', 'input', 'code'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'code' => 'required|unique:premium_services,code,' . $id,
            'name' => 'required|string|max:255|unique:premium_services,name,' . $id,
            'cost' => 'required',
        ], [
            'code.required' => 'Mã dịch vụ không được để trống.',
            'code.unique' => 'Mã dịch vụ đã tồn tại.',

            'name.required' => 'Tên dịch vụ không được để trống.',
            'name.string' => 'Tên dịch vụ phải là chuỗi ký tự.',
            'name.max' => 'Tên dịch vụ không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên dịch vụ đã tồn tại.',

            'cost.required' => 'Chi phí không được để trống.',
        ]);

        $cost = str_replace('.', '', $request->cost);
        $cost = (int) $cost;

        if ($id) {
            $premiumService = PremiumService::findOrFail($id);
            $notification = 'Dịch vụ đã được cập nhật thành công';
        } else {
            $premiumService = new PremiumService();
            $notification = 'Dịch vụ đã được thêm thành công';
        }
        $premiumService->code = $request->code;
        $premiumService->name = $request->name;
        $premiumService->unit_code = unitCode();
        $premiumService->subdomain = subdomain();
        $premiumService->cost = $cost;

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
        $input = $request->input('search');
        $room_code = $request->input('room_code');
        $check_in_id = $request->input('check_in_id');
        $serviceInRoom = RoomServiceProduct::where('room_code', $room_code)
            ->where('check_in_id', $check_in_id)
            ->with('product', 'service')->get();

        $serviceInRoom->each(function ($item) {
            if (!is_null($item->product)) {
                $item->type = 'product';
            } elseif (!is_null($item->service)) {
                $item->type = 'premium_service';
            } else {
                $item->type = null; // fallback nếu cần
            }
        });

        $premiumServices = PremiumService::query()
            ->active();


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

        $products = $products->select('id', 'name', 'selling_price as price', 'image_path')->get()->map(function ($item) {
            $item->type = 'product';
            return $item;
        });
        $data = array_values(array_merge(
            $premiumServices->toArray(),
            $products->toArray()
        ));


        return response()->json(['status' => 'success', 'data' => $data, 'serviceInRoom' => $serviceInRoom]);
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
                $existing->quantity       = $product['quantity'];
                $existing->price          = $product['price'];
                $existing->total_payment  = $product['quantity'] * $existing->price;
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
                    'subdomain'     => subdomain(),
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
                $existing->quantity       = $service['quantity'];
                $existing->price          = $service['price'];
                $existing->total_payment  = $service['quantity'] * $existing->price;
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
                      'subdomain'     => subdomain(),
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
    public function deleteService(Request $request)
    {
        DB::beginTransaction();
        try {
            $checkInId = $request->input('check_in_id');
            $roomCode = $request->input('room_id');
            $serviceId = $request->input('id_service');

            $service = RoomServiceProduct::where('check_in_id', $checkInId)
                ->where('room_code', $roomCode)
                ->where(function ($query) use ($serviceId) {
                    $query->where('product_id', $serviceId)
                        ->orWhere('service_id', $serviceId);
                })
                ->first();
            if (!$service) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Dịch vụ không tồn tại hoặc đã bị xoá trước đó.'
                ], 404);
            }
            // cập nhật lại bên thanh toán
            $receiptAndPayment = ReceiptAndPayment::where('checkin_id', $service->check_in_id)
                ->where('room_code', $service->room_code)
                ->first();
            // cập nhật lại giá dịch vụ 
            if ($receiptAndPayment) {
                if ($receiptAndPayment->service_fee !== null && $receiptAndPayment->service_fee != 0) {
                    $newServiceFee = $receiptAndPayment->service_fee - $service->total_payment;
                    $receiptAndPayment->update([
                        'service_fee' => $newServiceFee
                    ]);
                }
            }

            $service->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Xoá dịch vụ thành công.',
                'total_service' => RoomServiceProduct::where('check_in_id', $checkInId)
                    ->where('room_code', $roomCode)->sum('total_payment'),
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi xoá dịch vụ: ' . $e->getMessage());
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi trong quá trình xoá dịch vụ.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
