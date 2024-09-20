<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\StockEntry;
use App\Repositories\BaseRepository;
use App\Rules\StockCheck;

class InventoryController extends Controller
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new StockEntry());
    }
    public function index()
    {

        $pageTitle = "Biến động tồn kho";

        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        $columns = [
            'warehouse_entry_id',
            'product_id',
            'quantity',
            'entry_date',
        ];
        $relations = ['warehouse', 'product'];
        $searchColumns = [];
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

            // dd($response);

        if (request()->ajax()) {
            return response()->json([
                'results' => view('admin.table.inventory', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }

        return view('admin.inventory.index', compact('pageTitle'));
    }
}
