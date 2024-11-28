<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class AmenitiesController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Danh sách tiện nghi';
        $keyword = $request->input('keyword');

        $columns = Schema::getColumnListing('amenities');

        $amenities = Amenity::query()
            ->when($keyword, function ($query) use ($keyword, $columns) {
                $query->where(function ($query) use ($keyword, $columns) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, 'like', '%' . $keyword . '%');
                    }
                });
            })
        ->orderBy('title')
        ->paginate(getPaginate());

    // Trả về view kèm từ khóa và kết quả
        return view('admin.hotel.amenities', compact('pageTitle', 'amenities', 'keyword'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'code' => 'unique:amenities,code|max:6',
            'title'      => 'required|string|unique:amenities,title,' . $id,
            'icon'       => 'required'
        ]);

        if ($id) {
            $amenities          = Amenity::findOrFail($id);
            $notification       = 'Amenity updated successfully';
        } else {
            $amenities          = new Amenity();
            $notification       = 'Amenity added successfully';
        }
        $amenities->code = $request->code;
        $amenities->title      = $request->title;
        $amenities->icon       = $request->icon;
        $amenities->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Amenity::changeStatus($id);
    }



}
