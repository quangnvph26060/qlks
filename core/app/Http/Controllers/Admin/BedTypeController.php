<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BedType;

class BedTypeController extends Controller {
    public function index() {
        $pageTitle   = "Danh sách giường";
        $bedTypes = BedType::latest()->paginate(getPaginate());
        return view('admin.hotel.bed_type', compact('pageTitle', 'bedTypes'));
    }

    public function save(Request $request, $id = 0) {
        $request->validate([
            'name'        => 'required|string|unique:bed_types,name,' . $id
        ]);

        if ($id) {
            $bedType      = BedType::findOrFail($id);
            $notification = 'Đã cập nhật loại giường thành công';
        } else {
            $bedType      = new BedType();
            $notification = 'Đã thêm loại giường thành công';
        }

        $bedType->name = $request->name;
        $bedType->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function delete($id) {
        BedType::findOrFail($id)->delete();
        $notify[] = ['success', 'Đã xóa loại giường thành công'];
        return back()->withNotify($notify);
    }
}
