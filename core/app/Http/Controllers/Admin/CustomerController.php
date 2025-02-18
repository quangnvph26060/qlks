<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\helpers;
use App\Models\Customer;
use App\Models\RoomBooking;
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
            // 'email' => 'required|string',
            'customer_code' => 'required|string',
            'name' => 'required|string',
            // 'phone' => 'required|numeric',
           
        ]);
        $customer = new Customer();
        $customer->customer_code = $request->customer_code;
        $customer->name = $request->name;
        $customer->phone = $request->phone ?? '';
        $customer->email = $request->email ?? '';
        $customer->address = $request->address ?? '';
        $customer->group_code = $request->group_code ?? '';
        $customer->note = $request->note ?? '';
        $customer->status = $request->status;
        $customer->source_code = $request->source_code;
        $customer->unit_code =  unitCode();
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
            // 'phone' => 'required|numeric',
        ]);
        $customer = Customer::find($id);
        $code = $customer->customer_code;
        $bookings = RoomBooking::where('customer_code', $code)->where('unit_code',unitCode())->first();
        if($bookings)
        {
            $notify[] = ['error', 'Khách hàng đang có đơn hàng, không thể cập nhật'];
        }
        else
        {
            $customer->customer_code = $request->customer_code;
            $customer->name = $request->name;
            $customer->phone = $request->phone ?? '';
            $customer->email = $request->email ?? '';
            $customer->address = $request->address ?? '';
            $customer->group_code = $request->group_code ?? '';
            $customer->note = $request->note ?? '';
            $customer->source_code = $request->source_code;
            $customer->status = $request->status;
            // $customer->unit_code =  $request->unit_code;
            $customer->save();
            $notify[] = ['success', 'Cập nhật khách hàng thành công'];
        }
       
        return back()->withNotify($notify);
    }
    public function delete($id)
    {
        $customer = Customer::find($id);
        $code = $customer->customer_code;
        $bookings = RoomBooking::where('customer_code', $code)->where('unit_code',unitCode())->first();
        if ($bookings) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể xóa vì khách hàng đã có đơn hàng'
            ]);
        }
        else
        {
            Customer::destroy($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Xóa khách hàng thành công',
            ]);
        }
       
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
        $statusHtml = $newStatus == 1 ? ' <i class="fa fa-check" style="color:green;text-align: center"></i>' : '<i class="fa fa-close" style="color:red;text-align: center"></i>';
        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật trạng thái chức năng thành công',
            'status_html' => $statusHtml,
        ]);
        
    }
    public function search(Request $request)
    {
        $pageTitle = '';
        if($request->input('customer_code') == '' && $request->input('name') == '' && $request->input('phone') == '' && $request->input('address') == '')
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
                ->where('unit_code',unitCode())
                ->orderBy('id', 'desc')->paginate(30);
        }
        $customer_code = $request->input('customer_code');
        $name =  $request->input('name');
        $phone = $request->input('phone');
        $address = $request->input('address');
        return view('admin.hotel.customer.list', compact('pageTitle', 'customers','customer_code','name','address','phone'));
    }
    public function checkCode(Request $request)
    {
        $customer_code = $request->input('customer_code');
        $customer = new Customer();
        $user = $customer->select('customer_code')->where('customer_code', $customer_code)->first();
        // $user_delete = $customer->onlyTrashed()->select('customer_code')->where('customer_code', $customer_code)->first();
        if (!empty($user)) {
            return 1;
        }
        else
        {
            return 2;
        }
       
    }
 
}
