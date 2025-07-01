<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller {

    public function index() {
        $pageTitle = 'Tất cả nhân viên';
        // $allStaff = Admin::where('id', '!=', 1)->with('role')->paginate(getPaginate());
       //  $allStaff = Admin::where('id', '!=', auth('admin')->user()->id)->with('role')
        $allStaff = Admin::with('role')
        ->where('unit_code',unitCode())
        ->where('subdomain',subdomain())->paginate(getPaginate());
        $roles = Role::where('unit_code',unitCode())
        ->where('subdomain',subdomain())->get();
        return view('admin.staff.index', compact('pageTitle', 'allStaff', 'roles'));
    }

    public function status($id) {
        return Admin::changeStatus($id);
    }

    public function save(Request $request, $id = 0) {

        $this->validation($request, $id);
        if ($id) {
            $staff   = Admin::findOrFail($id);
            $message = "Nhân viên đã cập nhật thành công";
        } else {
            $staff   = new Admin();
            $message = "Đã thêm nhân viên mới thành công";
        }

        $staff->name        = $request->name;
        $staff->username    = $request->username;
        $staff->email       = $request->email;
        $staff->role_id     = $request->role_id;
        $staff->unit_code   = unitCode();
        $staff->subdomain   = subdomain();
        $staff->password    = $request->password ? Hash::make($request->password) : $staff->password;
        $staff->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    private function validation($request, $id) {
        $request->validate([
            'username'    => 'required|unique:admins,username,' . $id,
            'name'        => 'required',
            'email'       => 'required|unique:admins,email,' . $id,
            'role_id'     => 'required|integer|gt:0',
            'password'    => !$id ? 'required|min:6' : 'nullable',
        ]);
    }

    public function login($id) {
        if (!isSuperAdmin()) {
           abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }
        Auth::guard('admin')->loginUsingId($id);
        return to_route('admin.dashboard');
    }
}
