<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StatusCode;
use Illuminate\Http\Request;
use App\Models\SetupCode;

class StatusCodeController extends Controller
{

    public function index()
    {
        $count = StatusCode::where('unit_code',unitCode())->count();
        $code = SetupCode::where('menu_name','Danh mục trạng thái')->where('unit_code',unitCode())->value('code');
        $code = $code ? $code.$count+1 : '';
        $pageTitle = 'Trạng thái chức năng';
        $status_codes = StatusCode::orderBy('id', 'desc')->where('unit_code',unitCode())->paginate(10);;
        $emptyMessage = 'Không tìm thấy dữ liệu';
        return view('admin.hotel.status_code.list', compact('pageTitle', 'status_codes','code', 'emptyMessage'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'status_code' => 'required|string',
            'status_name' => 'required|string',
        ]);
        $status = new StatusCode();
        $status->status_code = $request->status_code;
        $status->status_name = $request->status_name;
        $status->note =  $request->note ?? '';
        $status->status_status = $request->status_status;
        $status->unit_code =  unitCode();
        $status->save();
        $notify[] = ['success', 'Thêm trạng thái chức năng thành công'];
        return back()->withNotify($notify);
    }
    public function edit($id)
    {
        if (!$id) {
            $notify[] = ['error', 'Không tìm thấy trạng thái'];
            return back()->withNotify($notify);
        }
        $status = StatusCode::find($id);
        return response()->json([
            'status' => 'success',
            'data' => $status,
        ]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'status_code' => 'required|string',
            'status_name' => 'required|string',
        ]);

        $status = StatusCode::find($id);
        $status->status_code = $request->status_code;
        $status->status_name = $request->status_name;
        $status->note =  $request->note ?? '';
        $status->status_status = $request->status_status;
        $status->save();

        $notify[] = ['success', 'Cập nhật trạng thái chức năng thành công'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        StatusCode::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa trạng thái chức năng thành công',
        ]);
    }
    // status
    public function status($id)
    {
        $status = StatusCode::find($id);

        if (!$status) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy trạng thái chức năng',
            ], 404);
        }

        // Đảo ngược trạng thái của khách sạn
        $newStatus = $status->status_status == 1 ? 0 : 1;

        $status->update(['status_status' => $newStatus]);
        $statusHtml = $newStatus == 1 ? '<span class="badge badge--success">Hoạt động</span>' : '<span class="badge badge--danger">Không hoạt động</span>';
        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật trạng thái chức năng thành công',
            'status_html' => $statusHtml,
        ]);
    }
    public function search(Request $request)
    {
        $count = StatusCode::where('unit_code',unitCode())->count();
        $code = SetupCode::where('menu_name','Danh mục trạng thái')->where('unit_code',unitCode())->value('code');
        $code = $code ? $code.$count+1 : '';
        $pageTitle = '';
        if($request->input('status_code') == '' && $request->input('status_name') == '')
        {
            $status_codes = StatusCode::orderBy('id', 'desc')->where('unit_code',unitCode())->paginate(10);
        }
        else
        {
            $status_codes = StatusCode::select('*')

                ->where('status_code','LIKE', '%'.$request->input('status_code').'%')
                ->where('status_name','LIKE', '%'.$request->input('status_name').'%')
                ->where('unit_code',unitCode())
                ->orderBy('id', 'desc')->paginate(10);
        }
        $emptyMessage = 'Không tìm thấy dữ liệu';

        return view('admin.hotel.status_code.list', compact('pageTitle','code', 'status_codes', 'emptyMessage'));
    }
}
