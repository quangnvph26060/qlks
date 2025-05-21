<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SetupCode;

use Illuminate\Http\Request;

class SetupController extends Controller
{
    public function index()
    {
        $pageTitle = 'Mã các danh mục';
        $setup_codes = SetupCode::orderBy('id', 'desc')->where('unit_code',unitCode())->paginate(10);
        
        $emptyMessage = 'Không tìm thấy dữ liệu';
        return view('admin.hotel.setup_code.list', compact('pageTitle', 'setup_codes', 'emptyMessage'));
    }
    public function store(Request $request)
    {
        // $request->validate([
        //     'code' => 'required|string',
        //     'menu_name' => 'required|string',
        // ]);
        $request->validate([
            'code' => 'required|string',
        ]); 
        $setup = new SetupCode();
        $setup->code = $request->code ?? '';
        $setup->menu_name = $request->menu_name ?? '';
        $setup->unit_code =   unitCode();
        $setup->subdomain =  subdomain();
        $setup->save();
        $notify[] = ['success', 'Thêm mã mặc định thành công'];
        return back()->withNotify($notify);
    }
    public function edit($id)
    {
        if (!$id) {
            $notify[] = ['error', 'Không tìm thấy mã'];
            return back()->withNotify($notify);
        }
        $setup = SetupCode::find($id);
        return $setup;
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);
        $source = SetupCode::find($id);
        $source->code = $request->code ?? '';
        $source->menu_name = $request->menu_name ?? '';
        // $source->unit_code =  $request->unit_code;
        $source->save();
        $notify[] = ['success', 'Cập nhật mã mặc định thành công'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        SetupCode::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa mã mặc định thành công',
        ]);
    }
    public function search(Request $request)
    {
        $pageTitle = '';
        if($request->input('code') == '' && $request->input('menu_name') == '')
        {
            $setup_codes = SetupCode::orderBy('id', 'desc')->where('unit_code',unitCode())->paginate(10);
        }
        else
        {
            $setup_codes = SetupCode::select('*')

                ->where('code','LIKE', '%'.$request->input('code').'%')
                ->where('menu_name','LIKE', '%'.$request->input('menu_name').'%')
                ->where('unit_code',unitCode())
                ->orderBy('id', 'desc')->paginate(10);
        }
        $emptyMessage = 'Không tìm thấy dữ liệu';

        return view('admin.hotel.setup_code.list', compact('pageTitle', 'setup_codes', 'emptyMessage'));
    }
}
