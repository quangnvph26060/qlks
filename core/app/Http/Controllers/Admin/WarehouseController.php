<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\SetupCode;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\WarehouseEntry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Warehouse;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new WarehouseEntry());
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = "Danh sách nhập hàng";

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
                'results' => view('admin.table.warehouse', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }

        $products   = Product::all();
        $categories = Category::query()->pluck('name', 'id');
        $suppliers  = Supplier::query()->pluck('name', 'id');
        $admin      = Admin::where('unit_code', unitCode())->where('subdomain', subdomain())->get();
        $warehouse  = Warehouse::active()->get();
        return view('admin.warehouse.index', compact('pageTitle', 'categories', 'suppliers', 'products', 'admin', 'warehouse'));
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info($request->all());
        $data = Validator::make(
            $request->all(),
            [
                'supplier_id' => 'required|not_in:null,0,""', // thêm các giá trị không hợp lệ
                'payment_method_id' => 'required|not_in:null,0,""',
            ],
            [
                'supplier_id.required' => 'Vui lòng chọn nhà cung cấp',
                'supplier_id.not_in' => 'Nhà cung cấp không hợp lệ',
                'payment_method_id.required' => 'Vui lòng chọn phương thức thanh toán',
                'payment_method_id.not_in' => 'Phương thức thanh toán không hợp lệ',
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

            $warehouse = WarehouseEntry::query()->create([
                'supplier_id'        => $request->get('supplier_id'),
                'reference_code'     => $this->repository->generateRandomString(),
                'created_time'       => now(),
                'payment_method_id'  => $request->get('payment_method_id'),
                'total'              => 0,
                'created_by' => (empty($request->get('employee_id')) || $request->get('employee_id') === 'null')
                    ? $request->get('employee_id')
                    : authAdmin()->id,

                'subdomain'          => subdomain(),
                'unit_code'          => unitCode(),
            ]);

            $products = $request->input('products', []);

            foreach ($products as $item) {
                $productId    = $item['product_id'];
                $quantity     = $item['quantity'];
                $warehouseId  = $item['warehouse_id'];
                $price        = $item['price'];

                $product = Product::find($productId);
                if (!$product) {
                    throw new \Exception("Sản phẩm ID $productId không tồn tại.");
                }

                // Cập nhật tồn kho
                $product->increment('stock', $quantity);

                // Tạo bản ghi chi tiết nhập
                $warehouse->entries()->create([
                    'product_id'    => $productId,
                    'quantity'      => $quantity,
                    'warehouse_id'  => $warehouseId,
                    'price'         => $price,
                    'type'          => 1
                ]);

                // Tính tổng
                $total += $quantity * $price;
            }

            // Cập nhật tổng tiền
            $warehouse->update([
                'total' => $total
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Tạo đơn hàng thành công.',
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
        $warehouse = WarehouseEntry::query()->with('supplier', 'return','entries.product')->find($id);
        $warehouses  = Warehouse::active()->get();
        $admin     = Admin::where('unit_code', unitCode())->where('subdomain', subdomain())->get();
        if (!$warehouse) {
            abort(404);
        }
        return view('admin.warehouse.show', compact('pageTitle', 'warehouse', 'suppliers', 'admin','warehouses'));
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
            $warehouse = WarehouseEntry::query()->with(['entries.product', 'stockEntries'])->find($id);

            $data = [];

            array_filter($warehouse->entries->toArray(), function ($value) use (&$data) {
                $product = Product::query()->find($value['product_id']);
                $product->increment('stock', $value['quantity'] - $value['number_of_cancellations']);

                $data[$value['product_id']] = [
                    'quantity' => $value['quantity'] - $value['number_of_cancellations'],
                    'entry_date' => now()->format('Y-m-d H:i:s')
                ];
            });

            $warehouse->stockEntries()->sync($data);

            $warehouse->update([
                'status' => 1,
                'confirmation_date' => now()->format('Y-m-d H:i:s'),
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật trạng thái thành công.'
            ]);
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
        //
    }
    // danh mục kho 
    public function Warehouse()
    {
        $code   = SetupCode::where('menu_name', 'Danh mục kho')->value('code');
        $count  = Warehouse::count();
        $code   = $code ? $code . $count + 1 : '';
        $warehouse = Warehouse::where('subdomain', subdomain())->paginate(10);
        $emptyMessage = 'Không tìm thấy dữ liệu';
        return view('admin.warehouse.warehouse', compact('warehouse', 'emptyMessage', 'code'));
    }
    public function addWarehouse(Request $request)
    {
        $request->merge([
            'code' => strtoupper(trim($request->code))
        ]);
        $request->validate([
            'code' => [
                'required',
                'string',
                'regex:/^[A-Z0-9\-]+$/'
            ],
            'name' => 'required|string',
        ]);
        $exists = Warehouse::where('code', $request->code)
            ->where('subdomain', subdomain())->exists();
        if ($exists) {
            return back()->withErrors(['code' => 'Mã kho đã tồn tại.'])->withInput();
        }

        $hotel = new Warehouse();
        $hotel->code = $request->code;
        $hotel->name = $request->name;
        $hotel->subdomain =  subdomain();
        $hotel->unit_code =  unitCode();
        $hotel->status =  $request->status;
        // save
        $hotel->save();


        $notify[] = ['success', 'Thêm kho thành công'];
        return back()->withNotify($notify);
    }
    public function editWarehouse($id)
    {
        if (!$id) {
            $notify[] = ['error', 'Không tìm thấy kho'];
            return back()->withNotify($notify);
        }
        $hotel = Warehouse::find($id);
        return response()->json([
            'status' => 'success',
            'data' => $hotel,
        ]);
    }
    public function updateWarehouse($id, Request $request)
    {
        $request->merge([
            'code' => strtoupper(trim($request->code))
        ]);
        $request->validate([
            'code' => [
                'required',
                'string',
                'regex:/^[A-Z0-9\-]+$/'
            ],
            'name' => 'required|string',
        ]);

        $hotel = Warehouse::find($id);
        $exists = Warehouse::where('code', $request->code)
            ->where('subdomain', subdomain())
            ->where('id', '!=', $id) // bỏ qua bản ghi đang sửa
            ->exists();

        if ($exists) {
            return back()->withErrors(['code' => 'Mã kho đã tồn tại.'])->withInput();
        }

        $hotel->code = $request->code;
        $hotel->name = $request->name;
        $hotel->subdomain =  subdomain();
        $hotel->unit_code =  unitCode();
        $hotel->status =  $request->status;

        $hotel->save();

        $notify[] = ['success', 'Cập nhật kho thành công'];
        return back()->withNotify($notify);
    }
    public function deleteWarehouse($id)
    {
        Warehouse::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa kho thành công',
        ]);
    }
}
