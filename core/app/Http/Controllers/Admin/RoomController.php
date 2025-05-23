<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\RoomType;
use App\Constants\Status;
use App\Models\RoomPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Models\SetupCode;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $pageTitle = 'Tất cả các loại phòng';
        $count = RoomType::count();
        $code = SetupCode::where('menu_name', 'Danh mục loại phòng')->value('code');
        $code = $code ? $code . $count + 1 : '';
        $keyword = $request->input('keyword');
        $columns = Schema::getColumnListing('room_types');
        $rooms = RoomType::query()
            ->when($keyword, function ($query) use ($keyword, $columns) {
                $query->where(function ($query) use ($keyword, $columns) {
                    foreach ($columns as $column) {
                        $query->orWhere($column, 'like', '%' . $keyword . '%');
                    }
                });
            })->orderBy('created_at');
        if (request()->status == Status::ENABLE || request()->status == Status::DISABLE) {
            $rooms = $rooms->filter(['status']);
        }
        $rooms = $rooms->paginate(10);

        return view('admin.hotel.rooms', compact('pageTitle', 'rooms', 'keyword', 'code'));
    }

    public function status($id)
    {
        $room = RoomType::findOrFail($id);
        $room->status = $room->status == Status::ENABLE ? Status::DISABLE : Status::ENABLE;
        $room->save();
        $notify[] = ['success', 'Cập nhật trạng thái thành công!'];
        return back()->withNotify($notify);
    }

    public function addRoom(Request $request, $id = 0)
    {
        $request->validate([
            'code' => [
                'required',
                Rule::unique('room_types', 'code')
                    ->ignore($id)
                    ->where('subdomain', subdomain()),
            ],
            'name' => [
                'required',
                Rule::unique('room_types', 'name')
                    ->ignore($id)
                    ->where('subdomain', subdomain()),
            ],
            'main_image' => 'image|nullable',
        ], [
            'code.required' => 'Mã loại phòng là bắt buộc.',
            'code.unique' => 'Mã loại phòng đã tồn tại.',
            'name.required' => 'Tên loại phòng là bắt buộc.',
            'name.unique' => 'Tên loại phòng đã tồn tại.',
            'main_image.image' => 'Ảnh đại diện phải là định dạng hình ảnh.',
        ]);

        if ($id) {
            $existsRoom = RoomType::where('name', $request->name)->where('id', '!=', $id)->exists();
        } else {
            $existsRoom = RoomType::where('name', $request->name)->first();
        }

        if ($existsRoom) {
            $notify[] = ['error', "Loại phòng yêu cầu đã tồn tại"];
            return back()->withNotify($notify);
        }
        if ($id) {
            $roomType = RoomType::findOrFail($id);
            $roomType->name = $request->name;
            $roomType->slug = Str::slug($roomType->name);
            $roomType->code  = $request->code;
            $roomType->status = $request->status;
            $roomType->unit_code = unitCode();
            $roomType->subdomain = subdomain();
            if ($request->hasFile('main_image')) {
                $main_images = saveImages($request, 'main_image', 'roomTypeImage', 600, 600);
                if ($main_images && count($main_images)) {
                    $roomType->main_image = $main_images[0];
                }
            }

            $roomType->save();

            $message = 'Loại phòng đã được cập nhật thành công';
        } else {
            $roomType = new RoomType();
            $roomType->name      = $request->name;
            $roomType->slug      = Str::slug($roomType->name);
            $roomType->code      = $request->code;
            $roomType->status    = $request->status;
            $roomType->unit_code = unitCode();
            $roomType->subdomain = subdomain();
            if ($request->hasFile('main_image')) {
                $main_images = saveImages($request, 'main_image', 'roomTypeImage', 600, 600);
                if ($main_images && count($main_images)) {
                    $roomType->main_image = $main_images[0];
                }
            }


            $roomType->save();

            $message = 'Loại phòng đã được thêm thành công';
        }
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }
    public function delete($id)
    {
        $roomType = RoomType::findOrFail($id);

        if ($roomType->main_image && Storage::disk('public')->exists($roomType->main_image)) {
            Storage::disk('public')->delete($roomType->main_image);
        }

        $roomType->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Loại phòng đã được xóa thành công'
        ]);
    }
}
