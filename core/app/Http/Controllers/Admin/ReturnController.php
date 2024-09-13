<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ReturnGood;
use App\Repositories\BaseRepository;

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
        $pageTitle = "Danh sách sản phẩm bị hoàn trả";

        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        $columns = ['id', 'supplier_id', 'product_id', 'reference_code', 'quantity', 'reason'];
        $relations = ['supplier', 'product'];
        $searchColumns = ['name', 'email', 'phone', 'address'];

        $response = $this->repository
            ->customPaginate(
                $columns,
                $relations,
                $perPage,
                $orderBy,
                $search,
                $searchColumns
            );


        if (request()->ajax()) {
            return response()->json([
                'results' => view('admin.table.return', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }
        return view('admin.return.index', compact('pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
