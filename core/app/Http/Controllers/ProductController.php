<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $pageTitle = 'Danh sách sản phẩm';
        $facilities = Facility::orderBy('title')->Paginate(getPaginate());
        return view('admin.hotel.facilities', compact('pageTitle', 'facilities'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'code' => 'unique:facilities,code|max:6',
            'title'       => 'required|string|unique:facilities,title,' . $id,
            'icon'        => 'required'
        ]);

        if ($id) {
            $facility           = Facility::findOrFail($id);
            $notification       = 'Product updated successfully';
        } else {
            $facility           = new Facility();
            $notification       = 'Product added successfully';
        }
        $facility->code         = $request->code;
        $facility->title        = $request->title;
        $facility->icon         = $request->icon;
        $facility->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Facility::changeStatus($id);
    }
}
