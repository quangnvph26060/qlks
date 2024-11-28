<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Danh sách cơ sở vật chất';
        // $facilities = Facility::orderBy('title')->Paginate(getPaginate());
        $keyword = $request->input('keyword');

        $columns = Schema::getColumnListing('amenities');

        $facilities = Facility::query()
            ->when($keyword, function ($query) use ($keyword, $columns) {
                $query->where(function ($query) use ($keyword, $columns) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, 'like', '%' . $keyword . '%');
                    }
                });
            })
        ->orderBy('title')
        ->paginate(getPaginate());
        return view('admin.hotel.facilities', compact('pageTitle', 'facilities', 'keyword'));
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
            $notification       = 'Facility updated successfully';
        } else {
            $facility           = new Facility();
            $notification       = 'Facility added successfully';
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
