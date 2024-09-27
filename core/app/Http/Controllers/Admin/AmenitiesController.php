<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenitiesController extends Controller
{
    public function index()
    {
        $pageTitle = 'Danh sách tiện nghi';
        $amenities = Amenity::orderBy('title')->Paginate(getPaginate());
        return view('admin.hotel.amenities', compact('pageTitle', 'amenities'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'amenity_id' => 'unique:amenities,amenity_id|max:6',
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
        $amenities->amenity_id = $request->amenity_id;
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
