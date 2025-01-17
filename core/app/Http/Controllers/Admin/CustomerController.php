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
        $pageTitle = 'Tất cả khách hàng';
        $customers = Customer::paginate(30);
        $unit_codes = HotelFacility::select('ma_coso')->get();
        return view('admin.hotel.customer.list', compact('pageTitle', 'customers','unit_codes'));
    }
}
