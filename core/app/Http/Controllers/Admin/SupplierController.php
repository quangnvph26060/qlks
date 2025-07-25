<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Bank;
use App\Models\SetupCode;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Repositories\BaseRepository;
use App\Http\Requests\StoreSupplierRequest;
use Illuminate\Validation\Rule;

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
        $columns = ['id', 'name', 'email', 'phone', 'address', 'supplier_id'];
        $relations = ['supplier_representatives'];
        $searchColumns = ['name', 'email', 'phone', 'address'];
        $relationSearchColumns = ['supplier_representatives' => ['email', 'name']];
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
                $searchColumns,
                $relationSearchColumns
            );


        if (request()->ajax()) {
            return response()->json([
                'results' => view('admin.table.supplier', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }
        $code   = SetupCode::where('menu_name', 'Cài đặt nhà cung cấp')->value('code');
        $count  = Supplier::count();
        $code   = $code ? $code . $count + 1 : '';
        $pageTitle = "Thêm mới nhà cung cấp";
        $banks = Bank::query()->pluck('name', 'id');
        return view('admin.supplier.index', compact('response', 'pageTitle', 'banks', 'code'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $code   = SetupCode::where('menu_name', 'Cài đặt nhà cung cấp')->value('code');
    //     $count  = Supplier::count();
    //     $code   = $code ? $code . $count + 1 : '';
    //     $pageTitle = "Thêm mới nhà cung cấp";
    //     $banks = Bank::query()->pluck('name', 'id');

    //     return view('admin.supplier.create', compact('pageTitle', 'banks', 'code'));
    // }

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
                $data['suppliers']['unit_code'] = unitCode(); // ví dụ mã đơn vị
                $data['suppliers']['subdomain'] = subdomain(); // ví dụ subdomain
                $data['suppliers']['email']    =  $request->suppliers['email'];
                $data['suppliers']['tax_code']    =  $request->suppliers['tax_code'];
                $supplier                       = Supplier::create($data['suppliers']);

                // $supplier->supplier_representatives()->create($data['representatives']);

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
        $pageTitle = "Cập nhật thông tin nhà cung cấp";
        $banks = Bank::query()->pluck('name', 'id');
        $supplier = Supplier::find($id);
        // return view('admin.supplier.edit', compact('pageTitle', 'banks', 'supplier'));
        return response()->json([
            'status' => 'success',
            'data' => $supplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
        public function update(UpdateSupplierRequest $request, string $id)

    {
        if ($request->ajax()) {
            

            DB::beginTransaction();
            try {
                $supplier = Supplier::findOrFail($id); // nên dùng findOrFail để rõ lỗi
                $validated = $request->validated(); 
                $validated['is_active'] = $request->is_active ? 1 : 0;
                
                $supplier->update($validated);

                DB::commit();

                session()->flash('success', 'Cập nhật thông tin nhà cung cấp thành công.');

                return response()->json([
                    'status' => true,
                ]);
            } catch (\Exception $exception) {
                DB::rollBack();
                Log::error($exception->getMessage());

                return response()->json([
                    'status' => false,
                    'message' => 'Đã có lỗi xảy ra, vui lòng thử lại sau!',
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::query()->find($id);

        if (!$supplier) {
            return response()->json([
                'status' => false,
                'message' => "Dữ liệu không tồn tại trên hệ thống!"
            ]);
        }

        $supplier->delete();

        return response()->json([
            'status' => true,
            'message' => "Xóa thành công."
        ]);
    }
}
