<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolesController extends Controller
{

    public function index()
    {
        $roles = Role::where('unit_code', unitCode())->where('subdomain', subdomain())->get();
        $pageTitle = "Tất cả vai trò";
        return view('admin.roles.index', compact('roles', 'pageTitle'));
    }

    public function add()
    {
        $pageTitle = "Thêm mới vai trò";
        $permissionGroups = Permission::all()->groupBy('group');
        return view('admin.roles.add', compact('pageTitle', 'permissionGroups'));
    }

    public function edit($id)
    {
        $pageTitle = "Sửa vai trò";
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = $role->permissions->pluck('pivot.permission_id');
        $permissionGroups = Permission::all()->groupBy('group');
        // dd($permissionGroups);
        return view('admin.roles.add', compact('pageTitle', 'permissionGroups', 'role', 'permissions'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'name'          => 'required|string',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'required|integer',
        ]);

        if (!$id) {
            $role = new Role();
            $notification = 'Vai trò mới đã được thêm thành công';
        } else {
            $role = Role::findOrFail($id);
            $notification = 'Vai trò mới đã được cập nhật thành công';
        }
        $role->name = $request->name;
        $role->unit_code =  unitCode();
        $role->subdomain = subdomain();
        $role->save();

        $role->permissions()->sync($request->permissions);
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
    public function delete($id)
    {
        try {
            $role = Role::findOrFail($id);
            \Log::info("Xoá vai trò ID: " . $id);

            $role->permissions()->detach();
            $role->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Xoá vai trò thành công'
            ]);
        } catch (\Exception $e) {
            \Log::error('Lỗi xoá vai trò: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi khi xoá vai trò'
            ], 500);
        }
    }
}
