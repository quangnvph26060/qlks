<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockEntry;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\WarehouseEntryItem;
use App\Models\WarehouseExport;
use App\Models\WarehouseExportLog;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WarehouseExportController extends Controller
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new WarehouseExport());
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = "Danh sách xuất hàng";

        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        $columns = ['id', 'supplier_id', 'reference_code', 'total', 'status', 'created_at'];
        $relations = [];
        $searchColumns = ['reference_code'];
        $requiredRelations = [];
        $response = $this->repository
            ->customPaginate(
                $columns,
                $relations,
                $requiredRelations,
                $perPage,
                $orderBy,
                $search,
                [],
                $searchColumns
            );

        if (request()->ajax()) {
            return response()->json([
                'results' => view('admin.table.warehouse_export', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }

        $products   = Product::all();
        $categories = Category::query()->pluck('name', 'id');
        $suppliers  = Supplier::query()->pluck('name', 'id');
        $admin      = Admin::where('unit_code', unitCode())->where('subdomain', subdomain())->get();
        $warehouse  = Warehouse::active()->get();
        return view('admin.warehouse_exports.index', compact('pageTitle', 'categories', 'suppliers', 'products', 'admin', 'warehouse', 'response'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = "Thêm mới đơn hàng";
        $categories = Category::query()->pluck('name', 'id');
        $suppliers = Supplier::query()->pluck('name', 'id');

        return view('admin.warehouse.create', compact('pageTitle', 'categories', 'suppliers'));
    }

    public function print(Request $request, $id)
    {
        $warehouse = WarehouseExport::with('entriesexport', 'admin')->findOrFail($id);
        return view('admin.warehouse_exports.print', compact('warehouse'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = Validator::make(
            $request->all(),
            [
                'supplier_id' => 'required|not_in:null,0,""', // thêm các giá trị không hợp lệ
                'payment_method_id' => 'required|not_in:null,0,""',
                'warehouse_code'    => 'nullable|unique:warehouse_exports,reference_code',
            ],
            [
                'supplier_id.required' => 'Vui lòng chọn nhà cung cấp',
                'supplier_id.not_in' => 'Nhà cung cấp không hợp lệ',
                'payment_method_id.required' => 'Vui lòng chọn phương thức thanh toán',
                'payment_method_id.not_in' => 'Phương thức thanh toán không hợp lệ',
                'warehouse_code.unique' => 'Mã phiếu đã tồn tại',
            ]
        );

        if ($data->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $data->errors()
            ]);
        }

        DB::beginTransaction();

        try {
            $total = 0;
            $employeeId = $request->get('employee_id');

            $createdBy = (!empty($employeeId) && $employeeId !== 'null' && $employeeId != 0)
                ? $employeeId
                : authAdmin()->id;
            $warehouse = WarehouseExport::query()->create([
                'supplier_id'        => $request->get('supplier_id'),
                'reference_code'     => filled($request->warehouse_code)
                    ? $request->warehouse_code
                    : getCode('PX', 12, WarehouseExport::class, 'reference_code'),


                'created_time'       => $request->date_warehouse ?? date('Y-m-d'),
                'payment_method_id'  => $request->get('payment_method_id'),
                'total'              => 0,
                'created_by'         => $createdBy,
                'note'               => $request->get('note'),
                'status'             => 1,
                'subdomain'          => subdomain(),
                'unit_code'          => unitCode(),
            ]);

            $products = $request->input('products', []);
            $data = [];
            foreach ($products as $item) {
                $productId    = $item['product_id'];
                $quantity     = $item['quantity'];
                $warehouseId  = $item['warehouse_id'];
                $price        = $item['price'];

                $product = Product::find($productId);
                if (!$product) {
                    throw new \Exception("Sản phẩm ID $productId không tồn tại.");
                }
                $product->decrement('stock', $quantity - $warehouse->number_of_cancellations);  // trừ số lượng sản phẩm 
                // Cập nhật tồn kho
                // $product->increment('stock', $quantity);

                // Tạo bản ghi chi tiết xuất
                WarehouseEntryItem::create([
                    'warehouse_export_id' => $warehouse->id,   // ID của phiếu xuất
                    'product_id'          => $productId,
                    'quantity'            => $quantity,
                    'warehouse_id'        => $warehouseId,
                    'price'               => $price,
                    'type'                => 0
                ]);


                // Tính tổng
                $total += $quantity * $price;
                // $data[$productId] = [
                //     'quantity'    => $quantity - $warehouse->number_of_cancellations,
                //     'entry_date'  => now()->format('Y-m-d H:i:s'),
                //     'status'      => 0
                // ];
            }

            // Cập nhật tổng tiền
            $warehouse->update([
                'total' => $total
            ]);
            // $warehouse->stockEntries()->sync($data);
             WarehouseExportLog::create([
                'warehouse_export_id' => $warehouse->id,
                'user_id'            => authAdmin()->id,
                'action'             => 'created',
            ]);
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Tạo xuất hàng thành công.',
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->repository->logError($exception);

            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = "Chi tiết đơn hàng";
        $suppliers = Supplier::query()->pluck('name', 'id');
        $warehouse = WarehouseExport::query()->with('supplier', 'returns', 'entries.product')->find($id);
        Log::info($warehouse);
        $warehouses  = Warehouse::active()->get();
        $admin     = Admin::where('unit_code', unitCode())->where('subdomain', subdomain())->get();
        if (!$warehouse) {
            abort(404);
        }
        return view('admin.warehouse_exports.show', compact('pageTitle', 'warehouse', 'suppliers', 'admin', 'warehouses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $warehouse = WarehouseExport::query()->with(['entriesexport.product'])->find($id);

            $data = [];

            array_filter($warehouse->entries->toArray(), function ($value) use (&$data) {
                $product = Product::query()->find($value['product_id']);
                $product->decrement('stock', $value['quantity'] - $value['number_of_cancellations']); // trừ số lượng

                $data[$value['product_id']] = [
                    'quantity'    => $value['quantity'] - $value['number_of_cancellations'],
                    'entry_date'  => now()->format('Y-m-d H:i:s'),
                    'status'      => 0
                ];
            });

            $warehouse->stockEntries()->sync($data);

            $warehouse->update([
                'status' => 1,
                'confirmation_date' => now()->format('Y-m-d H:i:s'),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Cập nhật trạng thái thành công.');
        } catch (\Exception $exception) {
            Log::error($exception);
            DB::rollBack();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $warehouse = WarehouseExport::with('entries', 'returns')->find($id);

        if (!$warehouse) {
            return back()->withErrors(['msg' => 'Không tìm thấy phiếu xuất.']);
        }
        // if ($warehouse->status == 1) {
        //     return back()->withErrors(['msg' => 'Phiếu xuất đã được xác nhận, không thể xoá.']);
        // }

        DB::beginTransaction();

        try {
            // Xoá các item (sản phẩm được nhập)
            foreach ($warehouse->entries as $entry) {
                // Trừ lại tồn kho nếu cần
                $product = $entry->product;
                if ($product) {
                    // $product->decrement('stock', $entry->quantity);
                }

                $entry->delete();
            }

            // Xoá các bản ghi trả hàng (nếu có)
            foreach ($warehouse->returns as $return) {
                $return->delete();
            }

            // Xoá phiếu nhập chính
            $warehouse->delete();

            DB::commit();
            return redirect()->route('admin.warehouse.export.index')->with('success', 'Xoá đơn xuất thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Xoá thất bại: ' . $e->getMessage()]);
        }
    }

    public function destroyWarehouseEntryItem($id)
    {
        $item = WarehouseEntryItem::find($id);
        Log::info($item);
        if (!$item) {
            return response()->json(['status' => false, 'message' => 'Không tìm thấy sản phẩm.'], 404);
        }

        DB::beginTransaction();

        try {
            $entry = WarehouseExport::find($item->warehouse_export_id);
            $itemTotal = $item->quantity * $item->price;
            StockEntry::where('product_id', $item->product_id)->where('warehouse_entry_id', $entry->id)->where('status', 0)->delete();
            // Xoá item
            $item->delete();

            // Cập nhật tổng tiền
            if ($entry) {
                $entry->total -= $itemTotal;
                if ($entry->total <= 0) {
                    // Nếu tổng tiền <= 0 → xoá luôn phiếu nhập
                    $entry->delete();

                    DB::commit();
                    return response()->json([
                        'status' => true,
                        'message' => 'Đã xoá sản phẩm và phiếu nhập.',
                        'entry_deleted' => true
                    ]);
                } else {
                    // Nếu còn tổng tiền > 0 → chỉ cập nhật
                    $entry->save();

                    DB::commit();
                    return response()->json([
                        'status' => true,
                        'message' => 'Xoá sản phẩm thành công.',
                        'entry_deleted' => false
                    ]);
                }
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Xoá thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Lỗi xoá: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateImportSlipe(Request $request)
    {
        $data = Validator::make(
            $request->all(),
            [
                'supplier_id'       => 'required|not_in:null,0,""',
                'payment_method_id' => 'required|not_in:null,0,""',
                'warehouse_code'    => 'nullable|unique:warehouse_exports,reference_code,' . $request->id,
            ],
            [
                'supplier_id.required' => 'Vui lòng chọn nhà cung cấp',
                'supplier_id.not_in' => 'Nhà cung cấp không hợp lệ',
                'payment_method_id.required' => 'Vui lòng chọn phương thức thanh toán',
                'payment_method_id.not_in' => 'Phương thức thanh toán không hợp lệ',
                'warehouse_code.unique' => 'Mã phiếu đã tồn tại',
            ]
        );
        if ($data->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $data->errors()
            ]);
        }
        $item = WarehouseExport::find($request->id);
        if (!$item) {
            return response()->json(['status' => false, 'message' => 'Không tìm thấy phiếu nhập.'], 404);
        }
        DB::beginTransaction();
        try {
            $item->payment_method_id = $request->payment_method_id;
            $item->created_by        = $request->created_by;
            $item->supplier_id       = $request->supplier_id;
            $item->note              = $request->note;
            $item->reference_code    =  filled($request->warehouse_code)
                ? $request->warehouse_code
                : getCode('PX', 12, WarehouseExport::class, 'reference_code');
            $item->created_time      = $request->dateWarehouse;

            $productItems = $request->productItems;
            $totalPrice = 0;
            foreach ($productItems as $product) {
                $warehouseItem = WarehouseEntryItem::find($product['item_id']);
                if (!$warehouseItem) {
                    continue;
                }

                $productModel = Product::find($product['product_id']);
                if (!$productModel) {
                    throw new \Exception("Sản phẩm ID {$product['product_id']} không tồn tại.");
                }
                $totalPrice += $product['quantity'] * $product['price'];
                if ($product['quantity'] == $warehouseItem->quantity) {
                    continue;
                }
                // Tính chênh lệch
                $oldQuantity = $warehouseItem->quantity;
                $newQuantity = $product['quantity'];
                $difference = $newQuantity - $oldQuantity;

                // Tính tồn kho mới
                $newStock = $productModel->stock - $difference;
                $productModel->stock = max(0, $newStock); // Nếu âm thì gán về 0
                // Cập nhật warehouse item
                $productModel->save();

                // Cập nhật warehouse item
                $warehouseItem->update([
                    'quantity' => $product['quantity'],
                    'warehouse_id' => $product['warehouse_id'],
                ]);
            }
            $item->total =  $totalPrice;
            $item->save();
             WarehouseExportLog::create([
                'warehouse_export_id' => $item->id,
                'user_id'            => authAdmin()->id,
                'action'             => 'updated',
            ]);

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Cập nhật thành công']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Lỗi xoá: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getLogs($id)
    {
        $logs = WarehouseExportLog::where('warehouse_export_id', $id)
            ->with('admin') // eager load thông tin user
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($log) {
                return [
                    'user_name'  => optional($log->admin)->name ?? 'Không rõ',
                    'timestamp' => $log->created_at->format('d/m/Y H:i:s'),
                    'action'    => $log->action === 'created' ? 'Tạo phiếu' : 'Cập nhật phiếu',
                ];
            });

        return response()->json($logs);
    }
}
