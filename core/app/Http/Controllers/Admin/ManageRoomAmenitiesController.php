<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Room;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ManageRoomAmenitiesController extends Controller
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new Room());
    }
    public function index()
    {
        $rooms = Room::all();
        $amenities = Amenity::all();
        $pageTitle = 'Danh sách tiện ghi của phòng';
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
        $relations = ['amenities', 'roomType'];
        $searchColumns = [
            'code',
        ];
        $relationSearchColumns = [];

        $response = $this->repository
            ->customPaginate(
                $columns,
                $relations,
                $perPage,
                $orderBy,
                $search,
                [],
                $searchColumns,
                $relationSearchColumns
            );

        if (request()->ajax()) {
            return response()->json([
                'results' => view('admin.table.manage-amenity-room', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }
        return view('admin.manage-room-amenities.index', compact('rooms', 'amenities'));
    }
    public function addAmenitiesToTheRoom(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'room_id' => 'required',
                'amenities_id' => 'required|array',
                'amenities_id.*' => 'exists:amenities,id',
            ],
            [
                'room_id.required' => 'Vui lòng chọn phòng.',
                'amenities_id.required' => 'Vui lòng chọn tiện nghi.',
                'amenities_id.array' => 'Danh sách tiện nghi không hợp lệ.',
                'amenities_id.*.exists' => 'Một hoặc nhiều tiện nghi không tồn tại.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'key' => $validator->errors()->keys()[0],
            ]);
        }

        $room = Room::find($request->room_id);
        if ($room) {
            $room->amenities()->sync($request->amenities_id);
        }

        return response()->json([
            'status' => true,
            'message' => 'Thao tác thành công!'
        ]);
    }

    public function edit($id)
    {
        $rooms = Room::select('id', 'code')->get();
        $roomEdit = Room::query()->find($id);
        $amenities = Amenity::select('id', 'title', 'code')->get();
        $selectedAmenities = $roomEdit->amenities->pluck('id')->toArray();
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
            'amenities' => $amenities,
            'selectedAmenities' => $selectedAmenities,
        ]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'amenities_id' => 'nullable|array',
            'amenities_id.*' => 'exists:amenities,id',
        ]);
        $room = Room::find($request->room_id);
        $room->amenities()->sync($request->amenities_id ?? []);
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật tiện nghi cho phòng thành công!'
        ]);
    }
}
