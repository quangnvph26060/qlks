<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerSource;
use App\Models\HotelFacility;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use App\Models\SetupCode;
use Illuminate\Validation\Rule;

class CustomerSourceController extends Controller
{
    public function index()
    {
        $code = SetupCode::where('menu_name', 'Danh mục nguồn khách hàng')->value('code');
        $count = CustomerSource::count();
        $code = $code ? $code . $count + 1 : '';
        $pageTitle = 'Nguồn khách hàng';
        $customer_sources = CustomerSource::orderBy('id', 'desc')->paginate(10);
        $unit_codes = HotelFacility::select('ma_coso')->get();
        $emptyMessage = 'Không tìm thấy dữ liệu';
        return view('admin.hotel.customer_source.list', compact('pageTitle', 'customer_sources', 'unit_codes', 'emptyMessage', 'code'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'source_code' => [
                'required',
                'string',
                'regex:/^[A-Z0-9]+$/',
                Rule::unique('customer_sources', 'source_code')
                    ->where(function ($query) {
                        $query->where('subdomain', subdomain());
                        $query->where('unit_code', unitCode());
                    }),
            ],
            'source_name' => [
                'required',
                'string',
                Rule::unique('customer_sources', 'source_name')
                    ->where(function ($query) {
                        $query->where('subdomain', subdomain());
                        $query->where('unit_code', unitCode());
                    }),
            ],
        ], [
            // Messages tiếng Việt cho source_code
            'source_code.required' => 'Mã nguồn không được để trống.',
            'source_code.string' => 'Mã nguồn phải là chuỗi ký tự.',
            'source_code.regex' => 'Mã nguồn phải viết hoa, chỉ chứa chữ cái in hoa và số.',
            'source_code.unique' => 'Mã nguồn đã tồn tại trong subdomain và đơn vị hiện tại.',

            // Messages tiếng Việt cho source_name
            'source_name.required' => 'Tên nguồn không được để trống.',
            'source_name.string' => 'Tên nguồn phải là chuỗi ký tự.',
            'source_name.unique' => 'Tên nguồn đã tồn tại trong subdomain và đơn vị hiện tại.',
        ]);

        $source = new CustomerSource();
        $source->source_code = $request->source_code;
        $source->source_name = $request->source_name;
        $source->subdomain   = subdomain();
        $source->unit_code   =  unitCode();
        $source->save();
        $notify[] = ['success', 'Thêm nguồn khách hàng thành công'];
        return back()->withNotify($notify);
    }
    public function edit($id)
    {
        if (!$id) {
            $notify[] = ['error', 'Không tìm thấy nguồn khách hàng'];
            return back()->withNotify($notify);
        }
        $source = CustomerSource::find($id);
        return $source;
    }

    public function update($id, Request $request)
    {
       $request->validate([
            'source_code' => [
                'required',
                'string',
                'regex:/^[A-Z0-9]+$/',
                Rule::unique('customer_sources', 'source_code')
                ->ignore($id)
                    ->where(function ($query) {
                        $query->where('subdomain', subdomain());
                        $query->where('unit_code', unitCode());
                    }),
            ],
            'source_name' => [
                'required',
                'string',
                Rule::unique('customer_sources', 'source_name')
                ->ignore($id)
                    ->where(function ($query) {
                        $query->where('subdomain', subdomain());
                        $query->where('unit_code', unitCode());
                    }),
            ],
        ], [
            // Messages tiếng Việt cho source_code
            'source_code.required' => 'Mã nguồn không được để trống.',
            'source_code.string' => 'Mã nguồn phải là chuỗi ký tự.',
            'source_code.regex' => 'Mã nguồn phải viết hoa, chỉ chứa chữ cái in hoa và số.',
            'source_code.unique' => 'Mã nguồn đã tồn tại trong subdomain và đơn vị hiện tại.',

            // Messages tiếng Việt cho source_name
            'source_name.required' => 'Tên nguồn không được để trống.',
            'source_name.string' => 'Tên nguồn phải là chuỗi ký tự.',
            'source_name.unique' => 'Tên nguồn đã tồn tại trong subdomain và đơn vị hiện tại.',
        ]);

        $source = CustomerSource::find($id);
        $source->source_code = $request->source_code;
        $source->source_name = $request->source_name;
        $source->subdomain   = subdomain();
        $source->unit_code   = unitCode();
        // $source->unit_code =  $request->unit_code;
        $source->save();
        $notify[] = ['success', 'Cập nhật nguồn khách hàng thành công'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        CustomerSource::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa nguồn khách hàng thành công',
        ]);
    }
    public function search(Request $request)
    {
        $code = SetupCode::where('menu_name', 'Danh mục nguồn khách')->where('unit_code', unitCode())->value('code');
        $count = CustomerSource::where('unit_code', unitCode())->count();
        $code = $code ? $code . $count + 1 : '';
        $pageTitle = 'Nguồn khách hàng';
        $pageTitle = '';
        if ($request->input('source_code') == '' && $request->input('source_name') == '') {
            $customer_sources = CustomerSource::orderBy('id', 'desc')->where('unit_code', unitCode())->paginate(10);
        } else {
            $customer_sources = CustomerSource::select('*')->where('unit_code', unitCode())

                ->where('source_code', 'LIKE', '%' . $request->input('source_code') . '%')
                ->where('source_name', 'LIKE', '%' . $request->input('source_name') . '%')
                ->where('unit_code', unitCode())
                ->orderBy('id', 'desc')->paginate(10);
        }
        return view('admin.hotel.customer_source.list', compact('pageTitle', 'code', 'customer_sources'));
    }
    //    protected $repository;

    //    public function __construct()
    //    {
    //        $this->repository = new BaseRepository (new CustomerSource());
    //    }
    // index controller
    //    public function index(){
    //        $pageTitle = "Nguồn khách hàng";
    //        $search = request()->get('search');
    //        $perPage = request()->get('perPage', 10);
    //        $orderBy = request()->get('orderBy', 'id');
    //        // $columns = ['id', 'name', 'status', 'category_id'];
    //        $columns = ['id', 'source_code', 'source_name','unit_code'];
    //        $relations = [];
    //        $searchColumns = ['name', 'status'];
    //
    //        $response = $this->repository
    //            ->customPaginate(
    //                $columns,
    //                $relations,
    //                $perPage,
    //                $orderBy,
    //                $search,
    //                [],
    //                $searchColumns,
    //                []
    //            );
    //
    //
    //        if (request()->ajax()) {
    //            return response()->json([
    //                'results' => view('admin.table.customer_source', compact('response'))->render(),
    //                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
    //            ]);
    //        }
    //        return view('admin.customer_source.index', compact('pageTitle','response'));
    //    }
}
