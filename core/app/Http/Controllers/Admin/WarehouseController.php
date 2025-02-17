<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\WarehouseEntry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
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
        $pageTitle = "Danh sách đơn hàng";

        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        $columns = ['id', 'supplier_id', 'reference_code', 'total', 'status', 'created_at'];
        $relations = ['supplier', 'return.return_items'];
        $searchColumns = ['reference_code'];

        $response = $this->repository
            ->customPaginate(
                $columns,
                $relations,
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
        return view('admin.warehouse.index', compact('pageTitle'));
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
        $data = Validator::make(
            $request->all(),
            [
                'supplier_id' => 'required',
                'payment_method_id' => 'required',
            ],
            [
                'supplier_id.required' => 'Vui lòng chọn nhà cung cấp',
                'payment_method_id.required' => 'Vui lòng chọn phương thức thanh toán',
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
            $warehouse = WarehouseEntry::query()->create([
                'supplier_id' => $request->get('supplier_id'),
                'reference_code' => $this->repository->generateRandomString(),
                'total' => 0,
            ]);

            $total = 0;

            if (count($request->input('products')) > 0) {
                foreach ($request->input('products') as $key => $value) {
                    $product = Product::query()->find($key);
                    $product->update([
                        'stock' => $product->stock - $value
                    ]);

                    $total += $product->import_price * $value;

                    $warehouse->entries()->create([
                        'product_id' => $key,
                        'quantity' => $value
                    ]);

                }
            }

            // $warehouse->payments()->create([
            //     'payment_method_id' => $request->get('payment_method_id'),
            //     'amount' => $total,
            //     'transaction_date' => now(),
            // ]);

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
        // $warehouse = WarehouseEntry::query()->with('supplier', 'entries.product', 'payments.payment_method', 'return')->find($id);
        $warehouse = WarehouseEntry::query()->with('return')->find($id);
        Log::info($warehouse);
        if (!$warehouse) {
            abort(404);
        }
        return view('admin.warehouse.show', compact('pageTitle', 'warehouse'));
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
}
