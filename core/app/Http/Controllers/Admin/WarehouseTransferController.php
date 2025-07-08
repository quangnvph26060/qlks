<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\WarehouseEntry;
use App\Models\WarehouseEntryItem;
use App\Models\WarehouseExport;
use App\Models\WarehouseTransfer;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WarehouseTransferController extends Controller
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new WarehouseTransfer());
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = "Danh sách điều chuyển hàng";

        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        $columns = ['id', 'reference_code', 'status', 'created_at', 'from_warehouse_id', 'to_warehouse_id'];
        $relations = ['fromWarehouse', 'toWarehouse'];
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
                'results' => view('admin.table.transfer', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }

        $products   = Product::all();
        $categories = Category::query()->pluck('name', 'id');
        $suppliers  = Supplier::query()->pluck('name', 'id');
        $admin      = Admin::where('unit_code', unitCode())->where('subdomain', subdomain())->get();
        $warehouse  = Warehouse::active()->get();
        return view('admin.transfer.index', compact('pageTitle', 'categories', 'suppliers', 'products', 'admin', 'warehouse'));
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
    public function store(Request $request)
    {
        $data = Validator::make(
            $request->all(),
            [
                'supplier_id' => 'required|not_in:null,0,""',
                'payment_method_id' => 'required|not_in:null,0,""',
                'products' => 'required|array|min:1',
                'products.*.product_id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.price' => 'required|numeric|min:0',
                'products.*.warehouse_from_id' => 'required|not_in:null,0,""',
                'products.*.warehouse_id' => 'required|not_in:null,0,""',
            ],
            [
                'supplier_id.required' => 'Vui lòng chọn nhà cung cấp',
                'supplier_id.not_in' => 'Nhà cung cấp không hợp lệ',
                'payment_method_id.required' => 'Vui lòng chọn phương thức thanh toán.',
                'payment_method_id.not_in' => 'Phương thức thanh toán không hợp lệ.',

                'products.required' => 'Vui lòng thêm sản phẩm.',
                'products.array' => 'Dữ liệu sản phẩm không hợp lệ.',
                'products.min' => 'Phải có ít nhất một sản phẩm.',

                'products.*.product_id.required' => 'Vui lòng chọn sản phẩm.',
                'products.*.product_id.exists' => 'Sản phẩm không tồn tại.',

                'products.*.quantity.required' => 'Vui lòng nhập số lượng.',
                'products.*.quantity.integer' => 'Số lượng phải là số nguyên.',
                'products.*.quantity.min' => 'Số lượng phải lớn hơn 0.',

                'products.*.price.required' => 'Vui lòng nhập giá sản phẩm.',
                'products.*.price.numeric' => 'Giá sản phẩm phải là số.',
                'products.*.price.min' => 'Giá sản phẩm không được âm.',

                'products.*.warehouse_from_id.required' => 'Vui lòng chọn kho nguồn.',
                'products.*.warehouse_from_id.not_in' => 'Kho nguồn không hợp lệ.',

                'products.*.warehouse_id.required' => 'Vui lòng chọn kho đích.',
                'products.*.warehouse_id.not_in' => 'Kho đích không hợp lệ.',
            ]
        );
        $data->after(function ($validator) use ($request) {
            $products = $request->input('products', []);

            foreach ($products as $index => $item) {
                if (
                    isset($item['warehouse_from_id'], $item['warehouse_id']) &&
                    $item['warehouse_from_id'] == $item['warehouse_id']
                ) {
                    $validator->errors()->add(
                        "products.$index.warehouse_id",
                        "Kho đích không được trùng với kho nguồn."
                    );
                }
            }
        });

        if ($data->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $data->errors()
            ]);
        }

        DB::beginTransaction();

        try {
            $total = 0;
            $products = $request->input('products', []);

            // ✅ Tạo export trước
            $export = WarehouseExport::create([
                'reference_code' =>  getCode('PX', 12, WarehouseExport::class, 'reference_code'),
                'supplier_id'        => $request->get('supplier_id'),
                'created_time' => now(),
                'payment_method_id' => $request->get('payment_method_id'),
                'total' => 0,
                'created_by' => authAdmin()->id ?? null,
                'subdomain' => subdomain(),
                'unit_code' => unitCode(),
            ]);

            // ✅ Tạo entry sau
            $entry = WarehouseEntry::create([
                'reference_code' => getCode('PN', 12, WarehouseEntry::class, 'reference_code'),
                'supplier_id'        => $request->get('supplier_id'),
                'created_time' => now(),
                'payment_method_id' => $request->get('payment_method_id'),
                'total' => 0,
                'created_by' => authAdmin()->id ?? null,
                'subdomain' => subdomain(),
                'unit_code' => unitCode(),
            ]);
            Log::info($request->all());
            // ✅ Tạo export_items và entry_items cho từng sản phẩm
            foreach ($products as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                $fromWarehouse = $item['warehouse_from_id'];
                $toWarehouse = $item['warehouse_id'];

                $product = Product::find($productId);
                if (!$product) {
                    throw new \Exception("Sản phẩm ID $productId không tồn tại.");
                }

                // Xuất kho
                WarehouseEntryItem::create([
                    'warehouse_export_id' => $export->id,
                    'product_id' => $productId,
                    'warehouse_id' => $toWarehouse,
                    'quantity' => $quantity,
                    'price' => $price,
                    'type' => 0,
                ]);

                // Nhập kho
                $entry->entries()->create([
                    'product_id' => $productId,
                    'warehouse_id' => $fromWarehouse,
                    'quantity' => $quantity,
                    'price' => $price,
                    'type' => 1,
                ]);

                $total += $quantity * $price;
            }

            // Cập nhật tổng tiền
            $export->update(['total' => $total]);
            $entry->update(['total' => $total]);
            // ✅ Tạo phiếu điều chuyển
            WarehouseTransfer::create([
                'reference_code'    => getCode('DC', 12, WarehouseTransfer::class, 'reference_code'),
                'from_warehouse_id' => $toWarehouse,
                'to_warehouse_id'   => $fromWarehouse,
                'export_id'         => $export->reference_code,
                'entry_id'          => $entry->reference_code,
                'transfer_date'     => now(),
                'note'              => $request->get('note'),
                'subdomain'         => subdomain(),
                'unit_code'         => unitCode(),
                'created_by'        => (empty($request->get('employee_id')) || $request->get('employee_id') === 'null')
                    ? $request->get('employee_id')
                    : authAdmin()->id,
                'status'            => 0
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Tạo phiếu điều chuyển thành công.'
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->repository->logError($exception);

            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ]);
        }
    }
    public function show(string $id)
    {
        $pageTitle = "Chi tiết điều chuyển";
        $suppliers = Supplier::query()->pluck('name', 'id');
        $warehouse = WarehouseTransfer::query()->with('export', 'entry', 'export.entries', 'entry.entries')->find($id);
        $warehouses  = Warehouse::active()->get();
        $admin       = Admin::where('unit_code', unitCode())->where('subdomain', subdomain())->get();
        if (!$warehouse) {
            abort(404);
        }
        return view('admin.transfer.show', compact('pageTitle', 'warehouse', 'suppliers', 'admin', 'warehouses'));
    }
    public function destroy(string $id)
    {
        $transfer  = WarehouseTransfer::with('export', 'entry', 'export.entries', 'entry.entries')->find($id);

        if (!$transfer) {
            return back()->withErrors(['msg' => 'Không tìm thấy phiếu điều chuyển.']);
        }
        if ($transfer->status == 1) {
            return back()->withErrors(['msg' => 'Phiếu điều chuyển đã được xác nhận, không thể xoá.']);
        }

        DB::beginTransaction();

        try {
            // Xoá sản phẩm chi tiết trong phiếu xuất
            if ($transfer->export && $transfer->export->entries) {
                foreach ($transfer->export->entries as $exportItem) {
                    // Khôi phục tồn kho nếu cần (trả lại hàng đã xuất)
                    // $product = $exportItem->product;
                    // if ($product) {
                    //     $product->increment('stock', $exportItem->quantity);
                    // }

                    $exportItem->delete(); // xoá chi tiết phiếu xuất
                }

                $transfer->export->delete(); // xoá phiếu xuất
            }

            // Xoá sản phẩm chi tiết trong phiếu nhập
            if ($transfer->entry && $transfer->entry->entries) {
                foreach ($transfer->entry->entries as $entryItem) {
                    $entryItem->delete(); // xoá chi tiết phiếu nhập
                }

                $transfer->entry->delete(); // xoá phiếu nhập
            }

            // Cuối cùng xoá phiếu điều chuyển
            $transfer->delete();

            DB::commit();
            return redirect()->route('admin.warehouse.transfer.index')->with('success', 'Đã xoá phiếu điều chuyển thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Xoá thất bại: ' . $e->getMessage()]);
        }
    }
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $warehouse = WarehouseTransfer::query()->with('export', 'entry', 'entry.stockEntries', 'export.stockEntries', 'export.entries', 'entry.entries')->find($id);

            $exportStockData = [];
            foreach ($warehouse->export->entries as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->decrement('stock', $item->quantity);

                    $exportStockData[$item->product_id] = [
                        'quantity'   => $item->quantity,
                        'entry_date' => now()->format('Y-m-d H:i:s'),
                        'status'     => 0 // 0 = xuất
                    ];
                }
            }
            $warehouse->export->stockEntries()->sync($exportStockData);
            $warehouse->export->update([
                'status' => 1,
                'confirmation_date' => now()->format('Y-m-d H:i:s'),
            ]);
            // 2. Xử lý bản ghi stock_entries từ phiếu nhập (tăng tồn kho)
            $entryStockData = [];
            foreach ($warehouse->entry->entries as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);

                    $entryStockData[$item->product_id] = [
                        'quantity'   => $item->quantity,
                        'entry_date' => now()->format('Y-m-d H:i:s'),
                        'status'     => 1 // 1 = nhập
                    ];
                }
            }
            $warehouse->entry->stockEntries()->sync($entryStockData);
            $warehouse->entry->update([
                'status' => 1,
                'confirmation_date' => now()->format('Y-m-d H:i:s'),
            ]);
            // 3. Cập nhật trạng thái phiếu điều chuyển
            $warehouse->update([
                'status' => 1,
                'transfer_date' => now(),
            ]);


            DB::commit();
            return redirect()->back()->with('success', 'Cập nhật trạng thái thành công.');
        } catch (\Exception $exception) {
            Log::error($exception);
            DB::rollBack();
        }
    }
}
