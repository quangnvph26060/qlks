<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\SetupCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class FacilityController extends Controller
{
    // public function index(Request $request)
    // {
    //     $pageTitle = 'Danh sách cơ sở vật chất';
    //     // $facilities = Facility::orderBy('title')->Paginate(getPaginate());
    //     $keyword = $request->input('keyword');

    //     $columns = Schema::getColumnListing('amenities');

    //     $facilities = Facility::query()
    //         ->when($keyword, function ($query) use ($keyword, $columns) {
    //             $query->where(function ($query) use ($keyword, $columns) {
    //                 foreach ($columns as $column) {
    //                     $query->orWhere($column, 'like', '%' . $keyword . '%');
    //                 }
    //             });
    //         })
    //     ->orderBy('title')
    //     ->paginate(getPaginate());
    //     return view('admin.hotel.facilities', compact('pageTitle', 'facilities', 'keyword'));
    // }
    public function index(Request $request)
    {
        $pageTitle = 'Danh sách cơ sở vật chất';
        $count = Facility::count();
        $code = SetupCode::where('menu_name', 'Cài đặt tiện nghi')->value('code');
        $code = $code ? $code . $count + 1 : '';
        $facilities = Facility::orderBy('id', 'desc')->where('unit_code',unitCode())->paginate(10);;
        $emptyMessage = 'Không tìm thấy dữ liệu';
        return view('admin.hotel.setup.facility', compact('pageTitle', 'facilities', 'emptyMessage','code'));
    }
    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'code' => [
                'required',
                'string',
                'regex:/^[A-Z0-9\-]+$/',
                Rule::unique('facilities', 'code')
                    ->ignore($id)
                    ->where('unit_code', unitCode())
                    ->where('subdomain', subdomain()),
            ],
            'title' => [
                'required',
                'string',
                Rule::unique('facilities', 'title')
                    ->ignore($id)
                    ->where('unit_code', unitCode())
                    ->where('subdomain', subdomain()),
            ],
        ],[
            'code.required' => 'Mã vật chất không được để trống.',
            'code.unique' => 'Mã vật chất đã tồn tại.',
            'code.regex' => 'Mã vật chất phải ghi hoa.',

            'title.required' => 'Tên vật chất không được để trống.',
            'title.string' => 'Tên vật chất phải là chuỗi ký tự.',
            'title.max' => 'Tên vật chất không được vượt quá 255 ký tự.',
            'title.unique' => 'Tên vật chất đã tồn tại.',

            'cost.required' => 'Chi phí không được để trống.',
        ]);

        if ($id) {
            $facility           = Facility::findOrFail($id);
            $notification       = 'Cập nhật cơ sở vật chất thành công';
        } else {
            $facility           = new Facility();
            $notification       = 'Thêm cơ sở vật chất thành công';
        }
        $facility->code         = $request->code;
        $facility->title        = $request->title;
        $facility->icon         = $request->icon;
        $facility->status = $request->status;
        $facility->unit_code = unitCode();
        $facility->subdomain = subdomain();
        $facility->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
    public function edit($id)
    {
        if (!$id) {
            $notify[] = ['error', 'Không tìm thấy trạng thái'];
            return back()->withNotify($notify);
        }
        $status = Facility::find($id);
        return response()->json([
            'status' => 'success',
            'data' => $status,
        ]);
    }
    public function status($id)
    {
        return Facility::changeStatus($id);
    }
    public function delete($id)
    {
        Facility::destroy($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa trạng thái chức năng thành công',
        ]);
    }
    public function search(Request $request)
    {
        $pageTitle = '';
        if($request->input('code') == '' && $request->input('title') == '')
        {
            $facilities = Facility::where('unit_code',unitCode())->orderBy('id', 'desc')->paginate(10);
        }
        else
        {
            $facilities = Facility::select('*')

                ->where('code','LIKE', '%'.$request->input('code').'%')
                ->where('title','LIKE', '%'.$request->input('title').'%')
                ->where('unit_code',unitCode())
                ->orderBy('id', 'desc')->paginate(10);
        }
        $emptyMessage = 'Không tìm thấy dữ liệu';
        $code = $request->input('code');
        $title = $request->input('title');
        return view('admin.hotel.setup.facility', compact('pageTitle', 'facilities', 'emptyMessage','code','title'));
    }
}
