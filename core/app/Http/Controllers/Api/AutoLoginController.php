<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AutoLoginController extends Controller
{
    public function loginBySubdomain($subdomain)
    {

        // dd($subdomain);
        $admin = Admin::where('subdomain', $subdomain)->first();

        if (!$admin) {
            abort(403, 'Không tìm thấy tài khoản phù hợp với domain này.');
        }

        Auth::guard('admin')->login($admin);


        if (Auth::guard('admin')->check()) {
            logger('Login OK: ' . Auth::guard('admin')->user()->email);
        } else {
            logger('Login FAIL');
        }

        return redirect('/admin/dashboard');
    }
}
