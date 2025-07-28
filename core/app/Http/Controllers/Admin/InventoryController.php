<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product as ModelsProduct;
use App\Models\StockEntry;
use App\Models\Warehouse;
use App\Models\WarehouseEntry;
use App\Models\WarehouseExport;
use App\Repositories\BaseRepository;
use App\Rules\StockCheck;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class InventoryController extends Controller
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new StockEntry());
    }
    public function index()
    {
        $warehouses  = Warehouse::active()->get();


        // $search = request()->get('search');
        // $perPage = request()->get('perPage', 10);
        // $orderBy = request()->get('orderBy', 'id');
        // $columns = [
        //     'warehouse_entry_id',
        //     'product_id',
        //     'quantity',
        //     'entry_date',
        //     'status'
        // ];
        // $relations = ['warehouse', 'product'];
        // $searchColumns = ['entry_date'];
        // $relationSearchColumns = ['warehouse' => ['reference_code'], 'product' => ['sku']];
        // $requiredRelations = [];
        // $response = $this->repository
        //     ->customPaginate(
        //         $columns,
        //         $relations,
        //         $requiredRelations,
        //         $perPage,
        //         $orderBy,
        //         $search,
        //         [],
        //         $searchColumns,
        //         $relationSearchColumns
        //     );

        // // dd($response);

        // if (request()->ajax()) {
        //     return response()->json([
        //         'results' => view('admin.table.inventory', compact('response'))->render(),
        //         'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
        //     ]);
        // }    

        return view('admin.inventory.index', compact('warehouses'));
    }
    public function get(Request $request)
    {
        $warehouseId = $request->input('warehouse_id');


        $startDate = request('start_date') ? Carbon::parse(request('start_date'))->toDateString() : null;
        $endDate = request('end_date') ? Carbon::parse(request('end_date'))->toDateString() : null;
        // Query warehouse_entries
        $entries = WarehouseEntry::query()
            ->where('status', 1)
            ->whereDate('created_time', '<=', $endDate)
            ->with(['stockEntries', 'entries' => function ($query) use ($warehouseId) {
                if ($warehouseId !== 'all') {
                    $query->where('warehouse_id', $warehouseId);
                }
            }]);

        // Query warehouse_exports
        $exports = WarehouseExport::query()
            ->where('status', 1)
            ->whereDate('created_time', '<=', $endDate)
            ->with(['stockEntries', 'entriesexport' => function ($query) use ($warehouseId) {
                if ($warehouseId !== 'all') {
                    $query->where('warehouse_id', $warehouseId);
                }
            }]);
        $entryResults  = $entries->get();
        $exportResults = $exports->get();
        // Lấy kết quả
        $entryResults = $entries->get()->filter(function ($item) {
            return $item->entries->isNotEmpty(); // Loại bỏ bản ghi không có entries
        })->values();

        $exportResults = $exports->get()->filter(function ($item) {
            return $item->entriesexport->isNotEmpty(); // Loại bỏ bản ghi không có entriesexport
        })->values();

        // tồng tồn kho
        $totalEntryQty = $entryResults->pluck('entries')
            ->flatten()
            ->sum('quantity');

        $totalExportQty = $exportResults->pluck('entriesexport')
            ->flatten()
            ->sum('quantity');

        $totalProduct = $totalEntryQty - $totalExportQty;
        // Sản phẩm sắp hết còn 10 sản phẩm 
        $stockEntries = $entryResults->pluck('stockEntries')->flatten();
        //Sản phẩm sắp hết
        $lowStockCount = $stockEntries->filter(function ($entry) {
            return $entry->stock > 0 && $entry->stock < 10;
        })->count();

        // sản phẩm hết hàng
        $outOfStock = $stockEntries->filter(function ($entry) {
            return $entry->stock == 0;
        })->count();
        // tổng tiền tồn kho
        $entries_sum = $entryResults->sum('total') - $exportResults->sum('total');


        // tính tồn đầu
        $entries = $entryResults->flatMap(function ($entry) {
            return $entry->entries->map(function ($item) use ($entry) {
                $item->created_time = $entry->created_time;
                $item->action = 'import';
                return $item;
            });
        });

        $exports = $exportResults->flatMap(function ($entry) {
            return $entry->entriesexport->map(function ($item) use ($entry) {
                $item->created_time = $entry->created_time;
                $item->action = 'export';
                return $item;
            });
        });

        $all = $entries->merge($exports);


        $summary = [];

        foreach ($all as $item) {
            $productId = $item->product_id;

            // Chuyển created_time thành dạng Y-m-d để so sánh chính xác theo ngày
            $createdDate = Carbon::parse($item->created_time)->toDateString();

            // Khởi tạo nếu chưa có dữ liệu cho product_id
            if (!isset($summary[$productId])) {
                $summary[$productId] = [
                    'product_name' => ModelsProduct::find($productId)?->name,
                    'product_code' => ModelsProduct::find($productId)?->sku,
                    'ton_dau'      => 0,
                    'nhap'         => 0,
                    'xuat'         => 0,
                ];
            }

            // 👉 Tính tồn đầu: nếu ngày tạo < ngày bắt đầu
            if ($startDate && $createdDate < $startDate) {
                $summary[$productId]['ton_dau'] += $item->action === 'import'
                    ? $item->quantity
                    : -$item->quantity;

                Log::info("Tồn đầu | {$createdDate} < {$startDate}");
            }

            // 👉 Tính nhập/xuất trong khoảng [start_date, end_date]
            if (
                $startDate && $endDate &&
                $createdDate >= $startDate &&
                $createdDate <= $endDate
            ) {
                if ($item->action === 'import') {
                    $summary[$productId]['nhap'] += $item->quantity;
                } else {
                    $summary[$productId]['xuat'] += $item->quantity;
                }

                // Log::info("Trong khoảng | {$createdDate} từ {$startDate} đến {$endDate}");
            }
        }

        // 👉 Tính tồn cuối
        $collection  = collect($summary)->map(function ($item) {
            $item['ton_cuoi'] = $item['ton_dau'] + $item['nhap'] - $item['xuat'];
            return $item;
        })->values();


        $page = $request->get('page', 1); // trang hiện tại
        $perPage = 10; // số bản ghi mỗi trang
        $paginated = new LengthAwarePaginator(
            $collection->forPage($page, $perPage)->values(), // data cho trang hiện tại
            $collection->count(), // tổng số bản ghi
            $perPage,
            $page,
            ['path' => url()->current()] // để tạo link phân trang đúng nếu cần
        );

        return response()->json([
            'entries_sum'     => $entries_sum,
            'total_inventory' => $totalProduct,
            'lowStockCount'   => $lowStockCount,
            'outOfStock'      => $outOfStock, // sản phẩm sắp hết hàng
            'data'            => $paginated->items(), // chỉ dữ liệu của trang hiện tại
            'current_page'    => $paginated->currentPage(),
            'last_page'       => $paginated->lastPage(),
            'total'           => $paginated->total(),
            'per_page'        => $paginated->perPage(),
        ]);
    }
}
