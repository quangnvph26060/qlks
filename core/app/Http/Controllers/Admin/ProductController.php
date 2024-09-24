<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;

class ProductController extends Controller
{

    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new Product());
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pageTitle = "Danh sách sản phẩm";

        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        $columns = [
            'id',
            'image_path',
            'name',
            'import_price',
            'selling_price',
            'category_id',
            'brand_id',
            'sku',
            'stock',
            'is_published',
        ];
        $relations = ['category', 'brand'];
        $searchColumns = [
            'name',
            'import_price',
            'selling_price',
            'sku',
        ];
        $relationSearchColumns = ['brand' => ['name'], 'category' => ['name']];

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


        if (request()->ajax()) {
            return response()->json([
                'results' => view('admin.table.product', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }
        return view('admin.product.index', compact('pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = "Thêm mới sản phẩm";
        $categories = Category::query()->pluck('name', 'id');
        $brands = Brand::query()->pluck('name', 'id');
        return view('admin.product.create', compact('brands', 'categories', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {

        $path = saveImages($request, 'image_path', 'products', 300, 300);

        try {
            $data = $request->validated();
            $data['image_path'] = $path[0];
            $data['is_published'] = $request->has('is_published') ? 1 : 0;

            Product::create($data);

            session()->flash('success', 'Thêm sản phẩm thành công!');

            return response()->json([
                'status' => true,
            ]);
        } catch (\Exception $e) {
            if ($path && Storage::disk('public')->exists($path[0])) {
                Storage::disk('public')->delete($path[0]);
            }

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
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
        $pageTitle = "Cập nhật sản phẩm";
        $product = Product::query()->find($id);
        $categories = Category::query()->pluck('name', 'id');
        $brands = Brand::query()->pluck('name', 'id');

        if (!$product) {
            return redirect()->route('admin.product.index')->with('error', 'Không tìm thấy sản phẩm!');
        }

        return view('admin.product.edit', compact('product', 'brands', 'categories', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $path = saveImages($request, 'image_path', 'products', 300, 300);
        $product = Product::query()->find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy sản phẩm!'
            ]);
        }

        try {
            $data = $request->validated();
            if ($path[0] && $path[0] != $product->image_path) {
                if (Storage::disk('public')->exists($product->image_path)) {
                    Storage::disk('public')->delete($product->image_path);
                }
                $data['image_path'] = $path[0];
            }
            $data['is_published'] = $request->has('is_published') ? 1 : 0;
            $product->update($data);

            session()->flash('success', 'Cập nhật sản phẩm thành công!');

            return response()->json([
                'status' => true,
            ]);
        } catch (\Exception $e) {
            if ($path[0] && Storage::disk('public')->exists($path[0])) {
                Storage::disk('public')->delete($path[0]);
            }

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = Product::query()->find($id);

            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => 'Không tìm thấy sản phẩm!'
                ]);
            }

            $product->delete();

            if (Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            return response()->json([
                'status' => true,
                'message' => 'Xoá sản phẩm thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Sản phẩm đã được sử dụng!'
            ]);
        }
    }

    public function updateStatus($id)
    {
        $product = Product::query()->find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy sản phẩm!'
            ]);
        }
        $product->is_published = !$product->is_published;

        $message = $product->is_published ? 'Sản phẩm đang được xuất bản.' : 'Sản phẩm đã ngưng phát hành.';
        $product->save();
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function filter(Request $request)
    {
        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');

        // Lấy các tiêu chí lọc khác
        $filters = [
            'category_id' => request()->get('category_id', []),
        ];

        $response = $this->repository->customPaginate(
            ['id', 'image_path', 'name', 'import_price', 'selling_price', 'category_id', 'stock', 'is_published'],
            [], // Thêm quan hệ nếu cần
            $perPage,
            $orderBy,
            $search,
            ['is_published' => true],
            ['name'],
            [],
            $filters,
            true,
        );

        if (request()->ajax()) {
            return response()->json([
                'results' => view('admin.table.warehouse-product', compact('response'))->render(),
            ]);
        }
    }
}
