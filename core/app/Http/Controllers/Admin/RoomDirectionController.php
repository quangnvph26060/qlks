<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SetupCode;
use Illuminate\Http\Request;

class RoomDirectionController extends Controller
{
    public function index()
    {
        $code   = SetupCode::where('menu_name', 'Danh mục khách hàng')->value('code');
        $count  = Customer::count();
        $code   = $code ? $code . $count + 1 : '';
        $pageTitle = '';
        return view('admin.hotel.room-direction.list', compact('pageTitle', 'code'));
    }
}
