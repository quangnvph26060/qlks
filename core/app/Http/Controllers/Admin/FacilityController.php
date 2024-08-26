<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Facilities';
        $facilities = Facility::orderBy('title')->Paginate(getPaginate());
        return view('admin.hotel.facilities', compact('pageTitle', 'facilities'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'title' => 'required|string|unique:facilities,title,' . $id,
            'icon'  => 'required'
        ]);

        if ($id) {
            $facility           = Facility::findOrFail($id);
            $notification       = 'Facility updated successfully';
        } else {
            $facility           = new Facility();
            $notification       = 'Facility added successfully';
        }
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
