<?php

namespace App\Http\Controllers\Admin;

use App\Models\ReturnGood;
use Illuminate\Http\Request;
use App\Models\WarehouseEntry;
use App\Models\WarehouseEntryItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Repositories\BaseRepository;
use App\Http\Requests\ReturnGoods\StoreReturnRequest;

class ReturnController extends Controller
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new ReturnGood());
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = "Danh sách đơn hàng bị hoàn trả";

        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        $columns = ['id', 'reference_code', 'total', 'created_at'];
        $relations = ['warehouse_entry', 'return_items'];
        $searchColumns = ['reference_code'];

        $response = $this->repository
            ->customPaginate(
                $columns,
                $relations,
                $perPage,
                $orderBy,
                $search,
                [],
                $searchColumns,
                ['warehouse_entry' => ['reference_code']],
                [],
                true


            );

        if (request()->ajax()) {
            return response()->json([
                'results' => view('admin.table.return', compact('response'))->render(),
            ]);
        }
        return view('admin.return.index', compact('pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $products = WarehouseEntryItem::query()->with('product')->where('warehouse_entry_id', $id)->get();

        return view('admin.return.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReturnRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $return = ReturnGood::create([
                'warehouse_entry_id' => $id,
                'reference_code' => $this->repository->generateRandomString(),
            ]);

            $return->return_items()->attach($request->products);

            $total = 0;

            $products = $return->return_items;

            foreach ($products as $product) {
                $total += $product->pivot->quantity * $product->import_price;
                $return->warehouse_entry->entries->where('product_id', $product->id)->first()->increment('number_of_cancellations', $product->pivot->quantity);
            }

            $return->update([
                'total' => $total
            ]);

            DB::commit();

            session()->flash('success', 'Thao tác thành công!');

            return response()->json(['status' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $products = ReturnGood::query()->with('return_items', 'warehouse_entry')->where('id', $id)->first();

        return view('admin.return.show', compact('products'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
