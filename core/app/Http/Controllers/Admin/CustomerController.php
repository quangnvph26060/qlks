<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\HotelFacility;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $pageTitle = '';
        $customers = Customer::orderBy('id', 'desc')->paginate(30);
        $unit_codes = HotelFacility::select('ma_coso')->get();
        return view('admin.hotel.customer.list', compact('pageTitle', 'customers', 'unit_codes'));
    }

//    public function index(){
//        $pageTitle = "Quản lý khách hàng";
//        return view('admin.customer.index', compact('pageTitle'));
//    }
    public function store(Request $request)
    {
        $request->validate([
            'customer_code' => 'required|string',
            'name' => 'required|string',
            'phone' => 'required|numeric',
            'email' => 'required|string',
        ]);
        $customer = new Customer();
        $customer->customer_code = $request->customer_code;
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->note = $request->note;
        $customer->status = $request->status;
        $customer->unit_code =  $request->unit_code;
        $customer->save();
        $notify[] = ['success', 'Thêm khách hàng thành công'];
        return back()->withNotify($notify);
    }
    public function edit($id)
    {
        if (!$id) {
            $notify[] = ['error', 'Không tìm thấy khách hàng'];
            return back()->withNotify($notify);
        }
        $source = Customer::find($id);
        return $source;
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'customer_code' => 'required|string',
            'name' => 'required|string',
            'phone' => 'required|numeric',
        ]);
        $customer = Customer::find($id);
        $customer->customer_code = $request->customer_code;
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->note = $request->note;
        $customer->status = $request->status;
        $customer->unit_code =  $request->unit_code;
        $customer->save();
        $notify[] = ['success', 'Cập nhật khách hàng thành công'];
        return back()->withNotify($notify);
    }
    public function delete($id)
    {
        Customer::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa khách hàng thành công',
        ]);
    }
    public function status($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy trạng thái chức năng',
            ], 404);
        }

        // Đảo ngược trạng thái của khách sạn
        $newStatus = $customer->status == 1 ? 0 : 1;

        $customer->update(['status' => $newStatus]);
        $statusHtml = $newStatus == 1 ? '<span class="badge badge--success">Hoạt động</span>' : '<span class="badge badge--danger">Không hoạt động</span>';
        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật trạng thái chức năng thành công',
            'status_html' => $statusHtml,
        ]);
    }
    public function search(Request $request)
    {
        $pageTitle = '';
        if($request->input('customer_code') == '' && $request->input('name') == '' && $request->input('phone') == ''  && $request->input('address') == ''  && $request->input('unit_code') == '')
        {
            $customers = Customer::orderBy('id', 'desc')->paginate(30);
        }
        else
        {
            $customers = Customer::select('*')
//                ->where(function ($query) use ($request) {
//                    $query ->where('customer_code','LIKE', '%'.$request->input('customer_code').'%');
//                })
                ->where('customer_code','LIKE', '%'.$request->input('customer_code').'%')
                ->where('name','LIKE', '%'.$request->input('name').'%')
                ->where('phone','LIKE', '%'.$request->input('phone').'%')
                ->where('address','LIKE', '%'.$request->input('address').'%')
                ->where('unit_code','LIKE', '%'.$request->input('unit_code').'%')
                ->orderBy('id', 'desc')->paginate(30);
        }
        $unit_codes = HotelFacility::select('ma_coso')->get();
        return view('admin.hotel.customer.list', compact('pageTitle', 'customers', 'unit_codes'));
    }
}
