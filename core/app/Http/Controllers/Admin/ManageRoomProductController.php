<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Product;
use App\Models\Room;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
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
        $searchColumns = [
            'code',
        ];
        $relationSearchColumns = [];

        $response = $this->repository
            ->customPaginate(
                $columns,
                $relations,
                $perPage,
                $orderBy,
                $search,
                [],
                $searchColumns,
                $relationSearchColumns
            );

        if (request()->ajax()) {
            return response()->json([
                'results' => view('admin.table.manage-product-room', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }

        return view('admin.manage-room-products.index', compact('rooms', 'products', 'pageTitle'));
    }
    public function store(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'room_id' => 'required',
                'product_id' => 'required|array',
                'product_id.*' => 'exists:products,id',
            ],
            [
                'room_id.required' => 'Vui lòng chọn phòng.',
                'product_id.required' => 'Vui lòng chọn sản phẩm.',
                'product_id.array' => 'Danh sách sản phẩm không hợp lệ.',
                'product_id.*.exists' => 'Một hoặc nhiều  không tồn tại.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'key' => $validator->errors()->keys()[0],
            ]);
        }

        $room = Room::find($request->room_id);

        if ($room) {
            $productIds = $request->product_id;
            $quantities = $request->stock;
            $syncData = [];
            foreach ($productIds as $index => $productId) {
                $syncData[$productId] = ['quantity' => $quantities[$index]];
                $product = Product::find($productId);
                $product->update(['stock' => $product->stock - $quantities[$index]]);
            }
            $room->products()->sync($syncData);
        }

        return response()->json([
            'status' => true,
            'message' => 'Thao tác thành công!'
        ]);
    }
    public function edit($id)
    {
        $rooms = Room::select('id', 'code')->where('status', 1)->get();
        $roomEdit = Room::query()->find($id);
        $products = product::select('id', 'name', 'stock')->where('is_published', 1)->where('stock', '>', 0)->get();
        $selectedproducts = $roomEdit->products->mapWithKeys(function ($product) {
            return [$product->id => $product->pivot->quantity];
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
            'selectedproducts' => $selectedproducts,
        ]);
    }
    public function update(Request $request)
    {

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'product_id' => 'nullable|array',
            'product_id.*' => 'exists:products,id',
        ]);
        $room = Room::find($request->room_id);

        $productIds = $request->product_id;
        $quantities = $request->stock;

        if ($productIds || $quantities) {
            $syncData = [];
            foreach ($productIds as $index => $productId) {
                $syncData[$productId] = ['quantity' => $quantities[$index]];
                $syncedProducts = $room->products()
                    ->where('room_id', $request->room_id)
                    ->where('product_id', $productId)->first()->pivot;
                $product = Product::find($productId);
                $product->update(['stock' => $product->stock + $syncedProducts->quantity - $quantities[$index]]);
            }

            $room->products()->sync($syncData);
        } else {
            $currentProducts = $room->products()->get();

            foreach ($currentProducts as $product) {
                $quantity = $product->pivot->quantity;

                $product->update([
                    'stock' => $product->stock + $quantity
                ]);
            }
            $room->products()->detach();
        }

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật sản phẩm cho phòng thành công!'
        ]);
    }
}
