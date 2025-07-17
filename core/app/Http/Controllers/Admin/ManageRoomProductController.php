<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Product;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\RoomProduct;
use App\Models\Warehouse;
use App\Models\WarehouseEntryItem;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Termwind\Components\Dd;

class ManageRoomProductController extends Controller
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new Room());
    }
    public function index()
    {
        $rooms = Room::where('status', 1)->get();
        $products = Product::where('is_published', 1)->where('stock', '>', 0)->get();
        $warehouse = Warehouse::active()->get();
        $pageTitle = 'Danh sách sản phẩm của phòng';
        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        $columns = [
            'id',
            'code',
            'room_type_id',
            'room_number',
            'description'
        ];
        $relations = ['products', 'roomType'];
        $requiredRelations = ['products'];
        $searchColumns = [
            'code'
        ];
        $relationSearchColumns = [];

        $response = $this->repository
            ->customPaginate(
                $columns,
                $relations,
                $requiredRelations,
                $perPage,
                $orderBy,
                $search,
                [],
                $searchColumns,
                $relationSearchColumns,

            );
        $room_type = RoomType::all();
        if (request()->ajax()) {
            return response()->json([
                'results' => view('admin.table.manage-product-room', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }

        return view('admin.manage-room-products.index', compact('rooms', 'products', 'pageTitle', 'room_type', 'warehouse'));
    }
    // public function index()
    // {
    //     $rooms = Room::where('unit_code',unitCode())->where('status' , 1)->paginate(10);
    //     $products = Product::where('is_published', 1)->where('stock', '>', 0)->get();
    //     $pageTitle = 'Danh sách sản phẩm của phòng';
    //     $room_type = RoomType::where('unit_code',unitCode())->get();

    //     return view('admin.manage-room-products.index', compact('rooms','room_type', 'products', 'pageTitle'));
    // }
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'room_ids' => 'required|array|min:1',
                'room_ids.*' => 'exists:rooms,id',
                'product_ids' => 'required|array|min:1',
                'product_ids.*' => 'exists:products,id',
                'stock' => 'required|array',
            ],
            [
                'room_ids.required' => 'Vui lòng chọn phòng.',
                'room_ids.*.exists' => 'Phòng không tồn tại.',
                'product_ids.required' => 'Vui lòng chọn sản phẩm.',
                'product_ids.*.exists' => 'Sản phẩm không tồn tại.',
                'stock.required' => 'Thiếu dữ liệu số lượng.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'key' => $validator->errors()->keys()[0],
            ]);
        }

        $unitCode = unitCode();
        $subdomain = subdomain();

        foreach ($request->product_ids as $productId) {
            $quantity = (int) ($request->stock[$productId] ?? 0);
            if ($quantity < 1) continue;

            $product = Product::find($productId);
            if (!$product) continue;
            $warehouseId = $request->warehouse_id[$productId] ?? null;
            if (!$warehouseId) continue;
            // Tổng số cần trừ: quantity x số phòng
            $totalQuantityRequired = $quantity * count($request->room_ids);

            // Kiểm tra đủ tồn kho
            if ($product->stock < $totalQuantityRequired) {
                return response()->json([
                    'status' => false,
                    'message' => "Sản phẩm {$product->name} không đủ tồn kho.",
                ]);
            }

            // Bắt đầu gán từng phòng
            foreach ($request->room_ids as $roomId) {
                $room = Room::find($roomId);
                if (!$room) continue;

                $existingPivot = $room->products()
                    ->where('products.id', $productId)
                    ->wherePivot('warehouse_id', $warehouseId) // nếu cần phân biệt theo warehouse
                    ->first();

                if (!$existingPivot) {
                    // Nếu chưa tồn tại, thêm mới
                    $room->products()->attach($productId, [
                        'quantity'       => $quantity,
                        'unit_code'      => $unitCode,
                        'subdomain'      => $subdomain,
                        'warehouse_id'   => $warehouseId,
                    ]);

                    $product->decrement('stock', $quantity);
                } else {
                    // Nếu đã tồn tại, cập nhật quantity (tăng thêm)
                    $currentQuantity = $existingPivot->pivot->quantity;

                    $room->products()->updateExistingPivot($productId, [
                        'quantity' => $currentQuantity + $quantity,
                    ]);

                    $product->decrement('stock', $quantity);
                }
                returnProductToWarehouseFromRoom($warehouseId, $productId, $quantity, $product->import_price, 'Xuất hàng', authAdmin()->id);
            }
        }


        return response()->json([
            'status' => true,
            'message' => 'Đã thêm sản phẩm vào các phòng thành công!',
        ]);
    }

    public function edit($id)
    {
        $rooms    = Room::select('id', 'code')->get();
        $roomEdit = Room::query()->find($id);
        $products = product::select('id', 'name', 'stock')->where('is_published', 1)->where('stock', '>', 0)->get();
        $selectedProducts = $roomEdit->products->mapWithKeys(function ($product) {
            return [
                $product->id => [
                    'quantity' => $product->pivot->quantity,
                    'warehouse_id' => $product->pivot->warehouse_id
                ]
            ];
        })->toArray();


        if (!$roomEdit) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không tồn tại trên hệ thống!'
            ]);
        }
        return response()->json([
            'status' => true,
            'rooms' => $rooms,
            'roomEdit' => $roomEdit,
            'products' => $products,
            'selectedproducts' => $selectedProducts,
        ]);
    }
    public function update(Request $request)
    {
        try {
            // Validate dữ liệu gửi lên
            $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'product_id' => 'nullable|array',
                'product_id.*' => 'exists:products,id',
                'stock' => 'nullable|array',
            ]);

            $room = Room::findOrFail($request->room_id);
            $productIds = $request->product_id ?? [];
            $quantities = $request->stock ?? [];

            $syncData = [];

            // Lấy danh sách ID sản phẩm hiện có trong phòng
            $currentProductIds = $room->products->pluck('id')->toArray();

            if (!empty($productIds)) {
                foreach ($productIds as $index => $productId) {
                    $quantity = (int) ($quantities[$index] ?? 0);
                    $product = Product::findOrFail($productId);
                    $warehouseId = $request->warehouse_id[$productId] ?? null;
                    if (!$warehouseId) continue;
                    // Kiểm tra xem sản phẩm đã có trong phòng chưa
                    $existing = $room->products()->where('product_id', $productId)->first();
                    $oldQuantity = $existing?->pivot->quantity ?? 0;
                    $oldWarehouseId = $existing?->pivot->warehouse_id ?? null;
                    // Nếu thay đổi số lượng hoặc đổi kho
                    if ($warehouseId != $oldWarehouseId || $quantity != $oldQuantity) {
                        // Trường hợp đổi kho
                        if ($warehouseId != $oldWarehouseId) {
                            if ($oldQuantity > 0 && $oldWarehouseId) {
                                Log::info("Trả lại về kho cũ (phiếu nhập)", compact('productId', 'oldWarehouseId', 'oldQuantity'));
                                addProductToWarehouse($request->room_id, $oldWarehouseId, $productId, $oldQuantity, $product->import_price, authAdmin()->id);
                            }

                            if ($quantity > 0 && $warehouseId) {
                                Log::info("Xuất hàng sang kho mới", compact('productId', 'warehouseId', 'quantity'));
                                returnProductToWarehouseFromRoom($warehouseId, $productId, $quantity, $product->import_price, "Chuyển kho", authAdmin()->id);
                            }
                        } elseif ($quantity != $oldQuantity) {
                            $diff = abs($quantity - $oldQuantity);

                            if ($quantity > $oldQuantity) {
                                Log::info("Tăng số lượng (phiếu xuất)", compact('productId', 'diff'));
                                returnProductToWarehouseFromRoom($warehouseId, $productId, $diff, $product->import_price, "Tăng số lượng", authAdmin()->id);
                            } else {
                                Log::info("Giảm số lượng (phiếu nhập)", compact('productId', 'diff'));
                                addProductToWarehouse($request->room_id, $warehouseId, $productId, $diff, $product->import_price, authAdmin()->id);
                            }
                        }
                    } else {
                        Log::info("Không thay đổi gì", compact('productId'));
                        // ❗️Vẫn thêm vào syncData để không bị xóa khi sync
                        $syncData[$productId] = [
                            'quantity'       => $oldQuantity,
                            'warehouse_id'   => $oldWarehouseId,
                            'unit_code'      => unitCode(),
                            'subdomain'      => subdomain(),
                        ];
                        continue;
                    }


                    // Cập nhật lại tồn kho (hoàn số cũ, trừ số mới)
                    $product->update(attributes: [
                        'stock' => $product->stock + $oldQuantity - $quantity,
                    ]);

                    // Gán dữ liệu cho pivot
                    $syncData[$productId] = [
                        'quantity'       => $quantity,
                        'warehouse_id'   => $warehouseId,
                        'unit_code'      => unitCode(),
                        'subdomain'      => subdomain(),
                    ];
                }

                // Xử lý các sản phẩm bị xoá (không còn trong request)
                $productsToRemove = array_diff($currentProductIds, $productIds);
                foreach ($productsToRemove as $productId) {
                    $product = Product::find($productId);
                    $pivot = $room->products()->where('product_id', $productId)->first();

                    if ($product && $pivot) {
                        $product->update([
                            'stock' => $product->stock + $pivot->pivot->quantity,
                        ]);
                    }
                }

                // Cập nhật dữ liệu bảng pivot
                $room->products()->sync($syncData);
            } else {
                // Nếu không gửi sản phẩm nào → hoàn kho toàn bộ và xoá
                foreach ($room->products as $product) {
                    $product->update([
                        'stock' => $product->stock + $product->pivot->quantity,
                    ]);
                }
                $room->products()->detach();
            }

            return response()->json([
                'status' => true,
                'message' => 'Cập nhật sản phẩm cho phòng thành công!',
            ]);
        } catch (\Exception $e) {
            \Log::error('Lỗi cập nhật sản phẩm cho phòng', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage(),
                'line' => $e->getLine(),
            ], 500);
        }
    }



    public function search(Request $request)
    {
        if ($request->input('room_type_id') == '' && $request->input('code') == '') {
            $rooms =  Room::select('rooms.*')
                ->where('unit_code', unitCode())->where('status', 1)
                ->orderBy('id', 'desc')->paginate(10);
        } else {
            $rooms = Room::select('rooms.*')->where('room_type_id', 'LIKE', '%' . $request->input('room_type_id') . '%')
                ->where(function ($query) use ($request) {
                    $query->where('room_number', 'LIKE', '%' . $request->input('code') . '%')
                        ->orWhere('code', 'LIKE', '%' . $request->input('code') . '%');
                })
                ->where('unit_code', unitCode())->where('status', 1)

                ->orderBy('id', 'desc')->paginate(10);
        }
        $products = Product::where('is_published', 1)->where('stock', '>', 0)->get();
        $room_type = RoomType::where('unit_code', unitCode())->get();
        $code =  $request->input('code');
        $pageTitle = 'Danh sách cơ sở vật chất của phòng';
        return view('admin.manage-room-products.index', compact('rooms', 'products', 'pageTitle', 'room_type', 'code'));
    }
    public function ajax(Request $request)
    {

        $rooms = Room::select('*')
            ->where('unit_code', unitCode())->where('status', 1)

            ->where(function ($q) use ($request) {

                if ($request->room_type_id != '') {
                    $q->where('rooms.room_type_id', '=', $request->room_type_id);
                }
            })
            ->distinct()
            ->orderBy('id', 'desc')->paginate(10);
        return view('admin.manage-room-products.search', compact('rooms'));
    }
    public function delete($roomId)
    {
        // Lấy danh sách các sản phẩm đã gán vào phòng
        $roomProducts = RoomProduct::where('room_id', $roomId)->get();

        foreach ($roomProducts as $roomProduct) {
            // Lấy sản phẩm tương ứng
            $product = Product::find($roomProduct->product_id);

            if ($product) {
                // Cộng lại số lượng vào tồn kho
                $product->increment('stock', $roomProduct->quantity);
            }
            addProductToWarehouse($roomId, $roomProduct->warehouse_id, $roomProduct->product_id, $roomProduct->quantity, $product->import_price, authAdmin()->id);
        }
        // Xóa hết bản ghi gán sản phẩm vào phòng
        RoomProduct::where('room_id', $roomId)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa sản phẩm và cập nhật tồn kho thành công',
        ]);
    }
    public function getProductsByWarehouse(Request $request)
    {
        $query = WarehouseEntryItem::query();

        // 🔍 Lọc nếu truyền giá trị hợp lệ
        if (!is_null($request->warehouse_id) && $request->warehouse_id !== '') {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        if (!is_null($request->product_id) && $request->product_id !== '') {
            $query->where('product_id', $request->product_id);
        }

        $products = $query
            ->select(
                'product_id',
                DB::raw('SUM(CASE WHEN type = 1 THEN quantity WHEN type = 0 THEN -quantity ELSE 0 END) as quantity')
            )
            ->groupBy('product_id')
            ->with('product:id,name')
            ->get()
            ->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'name' => $item->product->name ?? 'Không rõ',
                    'quantity' => $item->quantity
                ];
            });

        return response()->json([
            'status' => true,
            'products' => $products
        ]);
    }
}
