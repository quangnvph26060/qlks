<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;

use function Termwind\render;

class CategoryController extends Controller
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new Category());
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pageTitle = "Danh sách danh mục";

        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        // $columns = ['id', 'name', 'status', 'category_id'];
        $columns = ['id', 'name', 'status','code'];
        $relations = ['products'];
        $searchColumns = ['name', 'status'];

        $response = $this->repository
            ->customPaginate(
                $columns,
                $relations,
                $perPage,
                $orderBy,
                $search,
                [],
                $searchColumns,
                []
            );


        if (request()->ajax()) {
            return response()->json([
                'results' => view('admin.table.category', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }

        return view('admin.category.index', compact('response', 'pageTitle'));
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
                    'code' => 'unique:categories,code|max:6',
                    'name' => 'required|unique:categories,name',
                    'description' => 'nullable',
                ],
                [
                    'code.max' => 'Mã danh mục không được quá 6 ký tự',
                    'code.unique' => 'Mã danh mục đã tồn tại',
                    'name.required' => 'Vui lòng nhập tên danh mục!',
                    'name.unique' => 'Tên danh mục đã tồn tại!'
                ]
            );


            if ($validated->passes()) {

                $data = $validated->validated();

                $data['status'] = $request->has('status') && $request->status === 'on' ? 1 : 0;

                $category = Category::create($data);

                return response()->json([
                    'status' => true,
                    'message' => 'Thêm thành công!',
                    "data" => $category
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => "Thêm dang mục thất bại!",
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

        $category = Category::query()
            ->select('id', 'name', 'description', 'status', 'category_id')
            ->find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Danh mục không tồn tại trên hệ thống!'
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Danh mục đã được hiển thị!',
            'data' => $category,
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
                    'category_id' => 'unique:categories, category_id|max:6',
                    'name' => [
                        'required',
                        Rule::unique('categories', 'name')->ignore($id)
                    ],
                    'description' => 'nullable',
                ],
                [
                    'category_id.max' => 'Mã danh mục không được quá 6 ký tự',
                    'category_id.unique' => 'Mã danh mục đã tồn tại',
                    'name.required' => 'Vui lòng nhập tên danh mục!',
                    'name.unique' => 'Tên danh mục đã tồn tại!'
                ]
            );


            if ($validated->passes()) {

                $data = $validated->validated();

                $data['status'] = $request->has('status') && $request->status === 'on' ? 1 : 0;

                Category::query()->find($id)->update($data);

                $category = Category::query()->withCount('products')->find($id);

                return response()->json([
                    'status' => true,
                    'message' => 'Cập nhật danh mục thành công!',
                    "data" => $category
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

        $category = Category::query()->find($id);

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

        $category = Category::query()->find($request->id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy danh mục!'
            ], 404);
        }

        $category->update([
            'status' => $category->status == 1 ? 0 : 1
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật thành công.'
        ]);
    }
}
