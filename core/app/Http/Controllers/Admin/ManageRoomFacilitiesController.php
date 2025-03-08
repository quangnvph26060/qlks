<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Room;
use App\Models\RoomType;

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
    // public function index()
    // {
    //     $rooms = Room::where('status', 1)->get();
    //     $facilities = Facility::where('status', 1)->get();
    //     $pageTitle = 'Danh sách cơ sở vật chất của phòng';
    //     $search = request()->get('search');
    //     $perPage = request()->get('perPage', 10);
    //     $orderBy = request()->get('orderBy', 'id');
    //     $columns = [
    //         'id',
    //         'code',
    //         'room_type_id',
    //         'room_number',
    //         'description'
    //     ];
    //     $relations = ['facilities', 'roomType'];
    //     $searchColumns = [
    //         'code',
    //     ];
    //     $relationSearchColumns = [];

    //     $response = $this->repository
    //         ->customPaginate(
    //             $columns,
    //             $relations,
    //             $perPage,
    //             $orderBy,
    //             $search,
    //             [],
    //             $searchColumns,
    //             $relationSearchColumns
    //         );

    //     if (request()->ajax()) {
    //         return response()->json([
    //             'results' => view('admin.table.manage-facility-room', compact('response'))->render(),
    //             'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
    //         ]);
    //     }
    //     return view('admin.manage-room-facilities.index', compact('rooms', 'facilities', 'pageTitle'));
    // }
    public function index()
    {
        $rooms = Room::where('unit_code',unitCode())->where('status' , 1)->paginate(10);
        $room_type = RoomType::where('unit_code',unitCode())->get();

        $facilities = Facility::where('status', 1)->get();
        $pageTitle = 'Danh sách cơ sở vật chất của phòng';
        
        return view('admin.manage-room-facilities.index', compact('rooms','room_type', 'facilities', 'pageTitle'));
    }
    public function search(Request $request)
    {
        if($request->input('room_type_id') == '' && $request->input('code') == '')
        {
            $rooms =  Room::select('rooms.*')
            ->where('unit_code',unitCode())->where('status' , 1)
            ->orderBy('id', 'desc')->paginate(10);
        }
        else
        {
            $rooms = Room::select('rooms.*')->where('room_type_id','LIKE', '%'.$request->input('room_type_id').'%')
            ->where(function ($query) use ($request) {
                $query->where('room_number', 'LIKE', '%'.$request->input('code').'%')
                    ->orWhere('code', 'LIKE', '%'.$request->input('code').'%');
                    })
                ->where('unit_code',unitCode())->where('status' , 1)

                ->orderBy('id', 'desc')->paginate(10);
        }
        $facilities = Facility::where('status', 1)->get();
        $room_type = RoomType::where('unit_code',unitCode())->get();
        $code =  $request->input('code');
        $pageTitle = 'Danh sách cơ sở vật chất của phòng';
        return view('admin.manage-room-facilities.index', compact('rooms', 'facilities','pageTitle','room_type','code'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'room_id' => 'required',
                'facilities_id' => 'required|array',
                'facilities_id.*' => 'exists:facilities,id',
            ],
            [
                'room_id.required' => 'Vui lòng chọn phòng.',
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

        $room = Room::find($request->room_id);
        if ($room) {
            $room->facilities()->sync($request->facilities_id);
        }

        return response()->json([
            'status' => true,
            'message' => 'Thao tác thành công!'
        ]);
    }
    public function edit($id)
    {
        $rooms = Room::select('id', 'code')->where('status' , 1)->get();
        $roomEdit = Room::query()->find($id);
        $facilities = Facility::select('id', 'title', 'code')->where('status' , 1)->get();
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
}
