<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\RoomFacility;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManageRoomFacilitiesController extends Controller
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new Room());
    }
    public function index()
    {
        $room_type = RoomType::all();
        $rooms = Room::where('status', 1)->get();
        $facilities = Facility::where('status', 1)->get();
        $pageTitle = 'Danh sách cơ sở vật chất của phòng';
        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        $columns = [
            'id',
            'code',
            'room_type_id',
            'room_number',
            'description'
        ];
        $relations = ['facilities', 'roomType'];
        $requiredRelations = ['facilities'];
        $searchColumns = [
            'code',
        ];
        $relationSearchColumns = [];
     
        $response = $this->repository
            ->customPaginate(
                $columns,
                $relations,
                $requiredRelations,
                $perPage,
                $orderBy,
                $search,
                [],
                $searchColumns,
                $relationSearchColumns
            );
        if (request()->ajax()) {
            return response()->json([
                'results' => view('admin.table.manage-facility-room', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }
        return view('admin.manage-room-facilities.index', compact('rooms', 'facilities', 'pageTitle', 'room_type'));
    }
    
    public function search(Request $request)
    {
        if ($request->input('room_type_id') == '' && $request->input('code') == '') {
            $rooms =  Room::select('rooms.*')
                ->where('status', 1)
                ->orderBy('id', 'desc')->paginate(10);
        } else {
            $rooms = Room::select('rooms.*')->where('room_type_id', 'LIKE', '%' . $request->input('room_type_id') . '%')
                ->where(function ($query) use ($request) {
                    $query->where('room_number', 'LIKE', '%' . $request->input('code') . '%')
                        ->orWhere('code', 'LIKE', '%' . $request->input('code') . '%');
                })
                ->where('status', 1)

                ->orderBy('id', 'desc')->paginate(10);
        }
        $facilities = Facility::where('status', 1)->get();
        $room_type = RoomType::all();
        $code =  $request->input('code');
        $pageTitle = 'Danh sách cơ sở vật chất của phòng';
        return view('admin.manage-room-facilities.index', compact('rooms', 'facilities', 'pageTitle', 'room_type', 'code'));
    }
    public function store(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'room_ids' => 'required|array|min:1',
            'room_ids.*' => 'exists:rooms,id',
            'facilities_id' => 'required|array|min:1',
            'facilities_id.*' => 'exists:facilities,id',
        ],
        [
            'room_ids.required' => 'Vui lòng chọn ít nhất 1 phòng.',
            'room_ids.array' => 'Danh sách phòng không hợp lệ.',
            'room_ids.*.exists' => 'Phòng không tồn tại.',
            'facilities_id.required' => 'Vui lòng chọn tiện nghi.',
            'facilities_id.array' => 'Danh sách tiện nghi không hợp lệ.',
            'facilities_id.*.exists' => 'Một hoặc nhiều tiện nghi không tồn tại.',
        ]
    );

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => $validator->errors()->first(),
            'key' => $validator->errors()->keys()[0],
        ]);
    }

    $unitCode = unitCode();
    $subdomain = subdomain();

    foreach ($request->room_ids as $roomId) {
        $room = Room::find($roomId);
        if ($room) {
            foreach ($request->facilities_id as $facilityId) {
                // Kiểm tra nếu chưa có thì mới attach
                if (!$room->facilities()->where('facilities.id', $facilityId)->exists()) {
                    $room->facilities()->attach($facilityId, [
                        'unit_code' => $unitCode,
                        'subdomain' => $subdomain,
                    ]);
                }
            }
        }
    }

    return response()->json([
        'status' => true,
        'message' => 'Đã thêm tiện nghi vào các phòng thành công!',
    ]);
}

    public function edit($id)
    {
        $rooms = Room::select('id', 'code')->where('status', 1)->get();
        $roomEdit = Room::query()->find($id);
        $facilities = Facility::select('id', 'title', 'code')->where('status', 1)->get();
        $selectedfacilities = $roomEdit->facilities->pluck('id')->toArray();
        if (!$roomEdit) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không tồn tại trên hệ thống!'
            ]);
        }
        return response()->json([
            'status' => true,
            'rooms' => $rooms,
            'roomEdit' => $roomEdit,
            'facilities' => $facilities,
            'selectedfacilities' => $selectedfacilities,
        ]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'facilities_id' => 'nullable|array',
            'facilities_id.*' => 'exists:facilities,id',
        ]);
        $room = Room::find($request->room_id);
        $room->facilities()->sync($request->facilities_id ?? []);
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật tiện nghi cho phòng thành công!'
        ]);
    }
    public function ajax(Request $request)
    {

        $rooms = Room::select('*')
            ->where('unit_code', unitCode())
            ->where(function ($q) use ($request) {

                if ($request->room_type_id != '') {
                    $q->where('rooms.room_type_id', '=', $request->room_type_id);
                }
            })
            ->distinct()
            ->orderBy('id', 'desc')->paginate(10);
        return view('admin.manage-room-facilities.search', compact('rooms'));
    }
    public function delete($id)
    {
        $room = RoomFacility::where('room_id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Xóa cơ sở vật chất thành công',
        ]);
    }
}
