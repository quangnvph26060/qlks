<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerSource;
use App\Models\HotelFacility;
use Illuminate\Http\Request;

class CustomerSourceController extends Controller
{
    public function index()
    {
        $pageTitle = 'Nguồn khách hàng';
        $customer_sources = CustomerSource::all();
        $unit_codes = HotelFacility::select('ma_coso')->get();
        $emptyMessage = 'Không tìm thấy dữ liệu';
        return view('admin.hotel.customer_source.list', compact('pageTitle', 'customer_sources','unit_codes', 'emptyMessage'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'source_code' => 'required|string',
            'source_name' => 'required|string',
        ]);
        $source = new CustomerSource();
        $source->source_code = $request->source_code;
        $source->source_name = $request->source_name;
        $source->unit_code =  $request->unit_code;
        $source->save();
        $notify[] = ['success', 'Thêm nguồn khách hàng thành công'];
        return back()->withNotify($notify);
    }
    public function edit($id)
    {
        if (!$id) {
            $notify[] = ['error', 'Không tìm thấy trạng thái'];
            return back()->withNotify($notify);
        }
        $source = CustomerSource::find($id);
        return $source;
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'source_code' => 'required|string',
            'source_name' => 'required|string',
        ]);

        $source = CustomerSource::find($id);
        $source->source_code = $request->source_code;
        $source->source_name = $request->source_name;
        $source->unit_code =  $request->unit_code;
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
    // status

}
