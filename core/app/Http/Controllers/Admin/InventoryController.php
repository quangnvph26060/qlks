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
use Product;

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
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $warehouseId = $request->input('warehouse_id');
        $start = date('Y-m-d 00:00:00', strtotime($startDate));
        $end   = date('Y-m-d 23:59:59', strtotime($endDate));
     
        // Query warehouse_entries
        $entries = WarehouseEntry::query()
            ->where('status', 1)
            ->whereBetween('confirmation_date', [$start, $end])->with(['stockEntries', 'entries' => function ($query) use ($warehouseId) {
                if ($warehouseId !== 'all') {
                    $query->where('warehouse_id', $warehouseId);
                }
            }]);;

        // Query warehouse_exports
        $exports = WarehouseExport::query()
            ->where('status', 1)
            ->whereBetween('confirmation_date', [$start, $end])->with(['stockEntries', 'entriesexport' => function ($query) use ($warehouseId) {
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
        $entries = $entryResults->pluck('entries')->flatten();
        $exportResults = $exportResults->pluck('entriesexport')->flatten();

        $all = collect($entries)->map(function ($item) {
            $item->action = 'import'; // dùng object thay vì mảng
            return $item;
        })->merge(
            collect($exportResults)->map(function ($item) {
                $item->action = 'export';
                return $item;
            })
        );

        $grouped = $all->groupBy('product_id');
        $fromDate = request('end_date') ? Carbon::parse(request('end_date')) : null;
        $result = $grouped->map(function ($items, $productId) use($fromDate) {
            $ton_dau = 0;
            $nhap = 0;
            $xuat = 0;
 
            foreach ($items as $item) {
                $created = $item->created_at;
                $created = Carbon::parse($item->created_at);

                // Tồn đầu: nếu thời gian tạo < from_date
                if ($fromDate && $created->lt($fromDate)) {
                    if ($item->action === 'import') {
                        $ton_dau += $item->quantity;
                    } else {
                        $ton_dau -= $item->quantity;
                    }
                }
                if ($item->action === 'import') {
                    $nhap += $item->quantity;
                } else {
                    $xuat += $item->quantity;
                }
            }

            $ton_cuoi = $ton_dau + $nhap - $xuat;

            return [
                'product_name' => ModelsProduct::find($productId)?->name,
                'product_code' => ModelsProduct::find($productId)?->sku,
                'ton_dau'    => $ton_dau,
                'nhap'       => $nhap,
                'xuat'       => $xuat,
                'ton_cuoi'   => $ton_cuoi
            ];
        })->values();


        return response()->json([
            'entries_sum'     => $entries_sum,
            'total_inventory' => $totalProduct, // tổng tồn kho
            'lowStockCount'   => $lowStockCount,
            'outOfStock'      => $outOfStock,
            'data'            => $result

        ]);
    }
}
