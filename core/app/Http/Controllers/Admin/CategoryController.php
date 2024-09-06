<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = "Danh sách danh mục";
        $categories = Category::query()->latest()->with('products')->get();
        return view('admin.category.index', compact('categories', 'pageTitle'));
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
                    'name' => 'required|unique:categories',
                ],
                [
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
                    'message' => 'Thêm danh mục thành công!',
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
            ->select('id', 'name', 'description', 'status')
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
                    'name' => [
                        'required',
                        Rule::unique('categories', 'name')->ignore($id)
                    ],
                    'description' => 'nullable',
                ],
                [
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
