<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Room;
use App\Models\RoomDirection;
use App\Models\SetupCode;
use Illuminate\Http\Request;

class RoomDirectionController extends Controller
{
    public function index(Request $request)
    {
        $name = $request->name;

        $query = RoomDirection::query();

        if (!empty($name)) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        $roomDirection = $query->paginate(10);

        // Tạo mã code hướng phòng
        $codePrefix = SetupCode::where('menu_name', 'Danh mục hướng phòng')->value('code');
        $count = RoomDirection::count();
        $code = $codePrefix ? $codePrefix . ($count + 1) : '';

        $pageTitle = 'Danh sách hướng phòng';

        return view('admin.hotel.room-direction.list', compact('pageTitle', 'code', 'roomDirection'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required|string|max:255',

        ]);

        $unit_code = unitCode();
        $subdomain = subdomain();

        $directionId = $request->input('direction_id');
        $priceOffset = $request->price_offset
            ? (int) str_replace(['.', ','], '', $request->price_offset)
            : null;
        // Nếu có ID → cập nhật
        if ($directionId) {
            $direction = RoomDirection::findOrFail($directionId);

            // Kiểm tra trùng name (trừ chính nó)
            $exists = RoomDirection::where('name', $request->name)
                ->where('unit_code', $unit_code)
                ->where('subdomain', $subdomain)
                ->where('id', '!=', $directionId)
                ->exists();

            if ($exists) {
                $notify[] = ['error', 'Tên hướng phòng đã tồn tại'];
                return back()->withNotify($notify);
            }

            $direction->update([
                'code' => $request->code,
                'name' => $request->name,
                'price_offset' => $priceOffset,
            ]);

            $notify[] = ['success', 'Cập nhật hướng phòng thành công!'];
            return back()->withNotify($notify);
        }

        // Nếu không có ID → thêm mới
        $exists = RoomDirection::where('name', $request->name)
            ->where('unit_code', $unit_code)
            ->where('subdomain', $subdomain)
            ->exists();

        if ($exists) {
            $notify[] = ['error', 'Hướng phòng đã tồn tại'];
            return back()->withNotify($notify);
        }

        $direction = RoomDirection::create([
            'code' => $request->code,
            'name' => $request->name,
            'price_offset' => $priceOffset,
            'unit_code' => $unit_code,
            'subdomain' => $subdomain,
        ]);

        $notify[] = ['success', 'Thêm hướng phòng thành công!'];
        return back()->withNotify($notify);
    }

    /**
     * Xóa hướng phòng
     */
    public function destroy($id)
    {
        $direction = RoomDirection::findOrFail($id);

        // Kiểm tra nếu đã có phòng sử dụng hướng này
        $roomUsing = Room::where('direction_id', $id)->exists();
        if ($roomUsing) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xoá vì đã có phòng sử dụng hướng này!',
            ]);
        }

        $direction->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xoá hướng phòng!',
        ]);
    }
}
