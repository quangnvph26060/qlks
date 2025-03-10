<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use App\Models\SetupCode;

class CustomerGroupController extends Controller
{
    public function index()
    {
        $code = SetupCode::where('menu_name','Danh mục nhóm khách')->where('unit_code',unitCode())->value('code');
        $count = CustomerGroup::where('unit_code',unitCode())->count();
        $code = $code ? $code.$count+1 : '';
        $pageTitle = 'Nhóm khách hàng';
        $customer_groups = CustomerGroup::orderBy('id', 'desc')->where('unit_code',unitCode())->paginate(10);
        $emptyMessage = 'Không tìm thấy dữ liệu';
        return view('admin.hotel.customer_group.list', compact('pageTitle', 'customer_groups', 'emptyMessage','code'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'group_code' => 'required|string',
            'group_name' => 'required|string',
        ]);
        $group = new CustomerGroup();
        $group->group_code = $request->group_code;
        $group->group_name = $request->group_name;
        $group->unit_code =  unitCode();
        $group->save();
        $notify[] = ['success', 'Thêm nhóm khách hàng thành công'];
        return back()->withNotify($notify);
    }
    public function edit($id)
    {
        if (!$id) {
            $notify[] = ['error', 'Không tìm thấy nguồn khách hàng'];
            return back()->withNotify($notify);
        }
        $group = CustomerGroup::find($id);
        return $group;
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'group_code' => 'required|string',
            'group_name' => 'required|string',
        ]);

        $group = CustomerGroup::find($id);
        $group->group_code = $request->group_code;
        $group->group_name = $request->group_name;
        // $source->unit_code =  $request->unit_code;
        $group->save();
        $notify[] = ['success', 'Cập nhật nhóm khách hàng thành công'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        CustomerGroup::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa nhóm khách hàng thành công',
        ]);
    }
    public function search(Request $request)
    {
        $code = SetupCode::where('menu_name','Danh mục nhóm khách')->where('unit_code',unitCode())->value('code');
        $count = CustomerGroup::where('unit_code',unitCode())->count();
        $code = $code ? $code.$count+1 : '';
        $pageTitle = '';
        if($request->input('group_code') == '' && $request->input('group_name') == '')
        {
            $customer_groups = CustomerGroup::where('unit_code',unitCode())->orderBy('id', 'desc')->paginate(10);
        }
        else
        {
            $customer_groups = CustomerGroup::select('*')

                ->where('group_code','LIKE', '%'.$request->input('group_code').'%')
                ->where('group_name','LIKE', '%'.$request->input('group_name').'%')

                ->where('unit_code',unitCode())
                ->orderBy('id', 'desc')->paginate(10);
        }
        $emptyMessage = 'Không tìm thấy dữ liệu';

        return view('admin.hotel.customer_group.list', compact('pageTitle','code', 'customer_groups', 'emptyMessage'));
    }
}
