<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new Brand());
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = "Danh sách thương hiệu";

        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        $columns = ['id', 'name', 'is_active', 'code'];
        $relations = ['products'];
        $searchColumns = ['name',];

        $response = $this->repository
            ->customPaginate(
                $columns,
                $relations,
                $perPage,
                $orderBy,
                $search,
                [],
                $searchColumns,
            );


        if (request()->ajax()) {
            return response()->json([
                'results' => view('admin.table.brand', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }

        return view('admin.brand.index', compact('response', 'pageTitle'));
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
        if ($request->ajax()) {
            $validated  = Validator::make(
                $request->all(),
                [
                    'code' => 'unique:brands,code|max:6',
                    'name' => 'required|unique:brands,name',
                    'description' => 'nullable',
                ],
                [
                    'code.max' => 'Mã thương hiệu không được vượt quá 6 ký tự',
                    'code.unique' => 'Mã thương hiệu đã tồn tại',
                    'name.required' => 'Vui lòng nhập tên thương hiệu!',
                    'name.unique' => 'Tên thương hiệu đã tồn tại!'
                ]
            );


            if ($validated->passes()) {

                $data = $validated->validated();

                $data['is_active'] = $request->has('is_active') && $request->status === 'on' ? 1 : 0;

                $brand = Brand::create($data);

                return response()->json([
                    'status' => true,
                    'message' => 'Thêm thành công!',
                    "data" => $brand
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => "Thêm thương hiệu thất bại!",
                'errors' => $validated->errors()
            ]);
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
        $brand = Brand::query()
            ->select('id', 'name', 'description', 'is_active', 'brand_id')
            ->find($id);

        if (!$brand) {
            return response()->json([
                'status' => false,
                'message' => 'Thương hiệu không tồn tại trên hệ thống!'
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Thương hiệu đã được hiển thị!',
            'data' => $brand,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if ($request->ajax()) {
            $validated  = Validator::make(
                $request->all(),
                [
                    'brand_id' => 'unique:brands,brand_id|max:6',
                    'name' => [
                        'required',
                        Rule::unique('brands', 'name')->ignore($id)
                    ],
                    'description' => 'nullable',
                ],
                [
                    'brand_id.unique' => 'Mã thương hiệu đã tồn tại',
                    'brand_id.max' => 'Mã thương hiệu không được quá 6 ký tự',
                    'name.required' => 'Vui lòng nhập tên thương hiệu!',
                    'name.unique' => 'Tên thương hiệu đã tồn tại!'
                ]
            );


            if ($validated->passes()) {

                $data = $validated->validated();

                $data['is_active'] = $request->has('is_active') && $request->is_active === 'on' ? 1 : 0;

                Brand::query()->find($id)->update($data);

                $brand = Brand::query()->withCount('products')->find($id);

                return response()->json([
                    'status' => true,
                    'message' => 'Cập nhật danh mục thành công!',
                    "data" => $brand
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => "Cập nhật dang mục thất bại!",
                'errors' => $validated->errors()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Brand::query()->find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy danh mục!'
            ]);
        }

        $category->delete();
        return response()->json([
            'status' => true,
            'message' => 'Xoá danh mục thành công!'
        ]);
    }

    public function updateStatus(Request $request)
    {

        $brand = Brand::query()->find($request->id);

        if (!$brand) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy danh mục!'
            ], 404);
        }

        $brand->update([
            'is_active' => $brand->is_active == 1 ? 0 : 1
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật thành công.'
        ]);
    }
}
