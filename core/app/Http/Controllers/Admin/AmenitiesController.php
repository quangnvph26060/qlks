<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\SetupCode;
use App\Models\StatusCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class AmenitiesController extends Controller
{
    // public function index(Request $request)
    // {
    //     $pageTitle = 'Danh sách tiện nghi';
    //     $keyword = $request->input('keyword');

    //     $columns = Schema::getColumnListing('amenities');

    //     $amenities = Amenity::query()
    //         ->when($keyword, function ($query) use ($keyword, $columns) {
    //             $query->where(function ($query) use ($keyword, $columns) {
    //                 foreach ($columns as $column) {
    //                     $query->orWhere($column, 'like', '%' . $keyword . '%');
    //                 }
    //             });
    //         })
    //     ->orderBy('title')
    //     ->paginate(getPaginate());

    // // Trả về view kèm từ khóa và kết quả
    //     return view('admin.hotel.amenities', compact('pageTitle', 'amenities', 'keyword'));
    // }
  public function index(Request $request)
    {
        $pageTitle = 'Danh sách tiện nghi';
        // $keyword = $request->input('keyword');

        // $columns = Schema::getColumnListing('amenities');

        // $amenities = Amenity::query()
        //     ->when($keyword, function ($query) use ($keyword, $columns) {
        //         $query->where(function ($query) use ($keyword, $columns) {
        //             foreach ($columns as $column) {
        //                 $query->orWhere($column, 'like', '%' . $keyword . '%');
        //             }
        //         });
        //     })
        // ->orderBy('title')
        // ->paginate(getPaginate());
        $count = Amenity::count();
        $code = SetupCode::where('menu_name', 'Cài đặt tiện nghi')->value('code');
        $code = $code ? $code . $count + 1 : '';
        $amenities = Amenity::orderBy('id', 'desc')->paginate(10);
        $emptyMessage = 'Không tìm thấy dữ liệu';
        return view('admin.hotel.setup.amenities', compact('pageTitle', 'amenities', 'emptyMessage','code'));
    }
    public function store(Request $request, $id = 0)
    {
        $request->validate([
            'code' => [
                'required',
                'string',
                'regex:/^[A-Z0-9\-]+$/',
                Rule::unique('amenities', 'code')
                    ->ignore($id)
                    ->where('unit_code', unitCode())
                    ->where('subdomain', subdomain()),
            ],
            'title' => [
                'required',
                'string',
                Rule::unique('amenities', 'title')
                    ->ignore($id)
                    ->where('unit_code', unitCode())
                    ->where('subdomain', subdomain()),
            ],
        ],[
            'code.required' => 'Mã tiện nghi không được để trống.',
            'code.unique' => 'Mã tiện nghi đã tồn tại.',
            'code.regex' => 'Mã tiện nghi phải ghi hoa.',

            'title.required' => 'Tên tiện nghi không được để trống.',
            'title.string' => 'Tên tiện nghi phải là chuỗi ký tự.',
            'title.max' => 'Tên tiện nghi không được vượt quá 255 ký tự.',
            'title.unique' => 'Tên tiện nghi đã tồn tại.',

            'cost.required' => 'Chi phí không được để trống.',
        ]);

        if ($id) {
            $amenities          = Amenity::findOrFail($id);
            $notification       = 'Cập nhật tiện ích thành công';
        } else {
            $amenities          = new Amenity();
            $notification       = 'Thêm tiện ích thành công';
        }
        $amenities->code = $request->code;
        $amenities->title      = $request->title;
        $amenities->icon       = $request->icon;
        $amenities->status       = $request->status;
        $amenities->unit_code = unitCode();
         $amenities->subdomain = subdomain();
        $amenities->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
    public function edit($id)
    {
        if (!$id) {
            $notify[] = ['error', 'Không tìm thấy trạng thái'];
            return back()->withNotify($notify);
        }
        $status = Amenity::find($id);
        return response()->json([
            'status' => 'success',
            'data' => $status,
        ]);
    }
    public function status($id)
    {
        return Amenity::changeStatus($id);
    }
    public function update($id, Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'title' => 'required|string',
        ]);

        $status = StatusCode::find($id);
        $status->status_code = $request->status_code;
        $status->status_name = $request->status_name;
        $status->note =  $request->note;
        $status->status_status = $request->status_status;
        $status->save();

        $notify[] = ['success', 'Cập nhật trạng thái chức năng thành công'];
        return back()->withNotify($notify);
    }
    public function delete($id)
    {
        Amenity::destroy($id);
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
            $amenities = Amenity::orderBy('id', 'desc')->paginate(10);
        }
        else
        {
            $amenities = Amenity::select('*')

                ->where('code','LIKE', '%'.$request->input('code').'%')
                ->where('title','LIKE', '%'.$request->input('title').'%')
                ->orderBy('id', 'desc')->paginate(10);
        }
        $emptyMessage = 'Không tìm thấy dữ liệu';
        $code = $request->input('code');
        $title = $request->input('title');
        return view('admin.hotel.setup.amenities', compact('pageTitle', 'amenities', 'emptyMessage','code','title'));
    }
}
