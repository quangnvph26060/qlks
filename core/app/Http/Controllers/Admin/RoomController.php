<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\RoomType;
use App\Constants\Status;
use App\Models\RoomPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index()
    {

        $pageTitle = 'Tất cả các loại phòng';
        // $roomTypes = RoomType::get();
        $rooms     = RoomType::orderByDesc('created_at');
        // $prices = RoomPrice::active()->pluck('name', 'id');


        if (request()->status == Status::ENABLE || request()->status == Status::DISABLE) {
            $rooms = $rooms->filter(['status']);
        }


        $rooms =  $rooms->paginate(getPaginate());


        return view('admin.hotel.rooms', compact('pageTitle', 'rooms'));
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
            'code' => 'max:6|unique:room_types,code,'.$id,
            'name' => 'required|unique:room_types,name,'.$id,
            'main_image' => 'mimes:jpg|nullable',
        ]);

        if ($id) {
            $existsRoom = RoomType::where('name', $request->name)->where('id', '!=', $id)->exists();
        } else {
            //  $existsRoom = Room::whereIn('room_number', $request->room_numbers)->count();
            $existsRoom = RoomType::where('name', $request->name)->first();
        }

        if ($existsRoom) {
            $notify[] = ['error', "Số phòng yêu cầu đã tồn tại"];
            return back()->withNotify($notify);
        }

        if ($id) {
            $roomType = RoomType::findOrFail($id);
            $roomType->name = $request->name;
            $roomType->slug = \Str::slug($roomType->name);
            $roomType->code  = $request->code;
            $roomType->status = Status::ROOM_TYPE_ACTIVE;
            if ($request->hasFile('main_image')) {
                $main_images = saveImages($request, 'main_image', 'roomTypeImage', 600, 600);
                if ($roomType->main_image && Storage::disk('public')->exists($roomType->main_image)) {
                    Storage::disk('public')->delete($roomType->main_image);
                }
                $roomType->main_image = $main_images[0];
            }
            $roomType->save();
            $message = 'Phòng đã được cập nhật thành công';
        } else {
            // foreach ($request->room_numbers as $roomNumber) {
            //     $room = new Room;
            //     $room->room_type_id = $request->room_type_id;
            //     $room->room_number = $roomNumber;
            //     $room->room_id = $request->room_id;
            //     $room->save();
            // }
            $roomType = new RoomType();
            $roomType->name = $request->name;
            $roomType->slug = \Str::slug($roomType->name);
            $roomType->code  = $request->code;
            $roomType->status = Status::ROOM_TYPE_ACTIVE;
            if ($request->hasFile('main_image')) {
                $main_images = saveImages($request, 'main_image', 'roomTypeImage', 600, 600);
                if ($roomType->main_image && Storage::disk('public')->exists($roomType->main_image)) {
                    Storage::disk('public')->delete($roomType->main_image);
                }
                $roomType->main_image = $main_images[0];
            }
            // dd($roomType);
            $roomType->save();

            $message = 'Phòng đã được thêm thành công';
        }

        // Đồng bộ hóa giá
        // if ($id) {
        //        $room->prices()->sync($request->input('prices'));
        // } else {
        //     Nếu bạn đã thêm nhiều phòng, có thể cần đồng bộ cho từng phòng
        //     foreach ($request->room_numbers as $roomNumber) {
        //         $room = Room::where('room_number', $roomNumber)->first();
        //         if ($room) {

        //              $room->prices()->sync($request->input('prices'));
        //         }
        //     }

        //     $room = Room::where('room_number', $request->room_numbers)->first();
        //     if ($room) {
        //         $room->updatePrices($request->input('prices'));
        //         //  $room->prices()->sync($request->input('prices'));
        //     }
        // }

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }
}
