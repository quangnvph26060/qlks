<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // index controller
    public function index(){
        $pageTitle = "Quản lý khách hàng";
        return view('admin.customer.index', compact('pageTitle'));
    }
}
