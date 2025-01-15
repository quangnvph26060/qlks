<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerSource;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class CustomerSourceController extends Controller
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository (new CustomerSource());
    }
    // index controller
    public function index(){
        $pageTitle = "Nguồn khách hàng";
        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        // $columns = ['id', 'name', 'status', 'category_id'];
        $columns = ['id', 'source_code', 'source_name','unit_code'];
        $relations = [];
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
                'results' => view('admin.table.customer_source', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }
        return view('admin.customer_source.index', compact('pageTitle','response'));
    }
}
