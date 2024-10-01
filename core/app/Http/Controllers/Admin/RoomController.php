<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\RoomType;
use App\Constants\Status;
use App\Models\RoomPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomController extends Controller
{
    public function index()
    {
        $pageTitle = 'Tất cả các phòng';
        $roomTypes = RoomType::get();
        $rooms     = Room::searchable(['room_number', 'roomType:name'])->filter(['room_type_id'])->orderBy('room_number')->with('prices:name');
        $prices = RoomPrice::active()->pluck('name', 'id');

        if (request()->status == Status::ENABLE || request()->status == Status::DISABLE) {
            $rooms = $rooms->filter(['status']);
        }

        $rooms =  $rooms->with('roomType.images')->orderBy('room_number', 'asc')->paginate(getPaginate());

        return view('admin.hotel.rooms', compact('pageTitle', 'rooms', 'roomTypes', 'prices'));
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
        $roomFiled = $id ? 'room_number' : 'room_numbers';


        $roomId = $id ?? '';


        $request->validate([
            // 'room_type_id' => 'required|exists:room_types,id',
            // "$roomFiled"   => 'required',
            // 'room_id'      => 'unique:rooms,room_id,' . $roomId,
            // 'prices'       => 'required|array', // Thêm điều kiện cho prices
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
            $roomType->code  = $request->code;
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
            $roomType = new Room;
            $roomType->name = $request->name;
            $roomType->code  = $request->code;
            $roomType->save();

            $message = 'Phòng đã được thêm thành công';
        }

        // Đồng bộ hóa giá
        if ($id) {
        //    $room->prices()->sync($request->input('prices'));
        } else {
            // Nếu bạn đã thêm nhiều phòng, có thể cần đồng bộ cho từng phòng
            // foreach ($request->room_numbers as $roomNumber) {
            //     $room = Room::where('room_number', $roomNumber)->first();
            //     if ($room) {

            //          $room->prices()->sync($request->input('prices'));
            //     }
            // }

            $room = Room::where('room_number', $request->room_numbers)->first();
            if ($room) {
                $room->updatePrices($request->input('prices'));
                //  $room->prices()->sync($request->input('prices'));
            }
        }

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }
}
