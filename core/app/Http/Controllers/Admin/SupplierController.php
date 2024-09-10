<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\BaseRepository;
use App\Http\Requests\StoreSupplierRequest;

class SupplierController extends Controller
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new Supplier());
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = "Danh sách nhà cung cấp";

        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        $columns = ['id', 'name', 'email', 'phone', 'address'];
        $relations = ['supplier_representatives'];
        $searchColumns = ['name', 'email', 'phone', 'address'];
        $relationSearchColumns = ['supplier_representatives' => ['email', 'name']];

        $response = $this->repository
            ->customPaginate(
                $columns,
                $relations,
                $perPage,
                $orderBy,
                $search,
                $searchColumns,
                $relationSearchColumns
            );


        if (request()->ajax()) {
            return response()->json([
                'results' => view('admin.table.supplier', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }

        return view('admin.supplier.index', compact('response', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = "Thêm mới nhà cung cấp";
        $banks = Bank::query()->pluck('name', 'id');
        return view('admin.supplier.create', compact('pageTitle', 'banks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {

        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                $data                           = $request->validated();

                $data['suppliers']['is_active'] = $request->is_active ? 1 : 0;

                $supplier                       = Supplier::create($data['suppliers']);

                $supplier->supplier_representatives()->create($data['representatives']);

                DB::commit();

                session()->flash('success', 'Thêm nhà cung cấp thành công.');

                return response()->json([
                    'status'    => true,
                ]);
            } catch (\Exception $exception) {
                DB::rollBack();

                $this->repository->logError($exception);

                return response()->json([
                    'status'    => false,
                    'message'   => 'Đã có lỗi xay ra, vui lòng thử lại sau!',
                ]);
            }
        }
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
