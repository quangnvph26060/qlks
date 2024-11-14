<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Validator;

class ManagePriceListController extends Controller
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new RoomPrice());
    }

    public function priceList()
    {


        $pageTitle = 'Danh sách bảng giá';
        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        $columns = [
            'id',
            'code',
            'name',
            'price',
            'start_date',
            'end_date',
            'status',
        ];
        $relations = [];
        $searchColumns = [
            'code',
            'name',
            'status',
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
                'results' => view('admin.table.manage-price', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }
        return view('admin.manage-price.index', compact('pageTitle'));
        $rooms = Room::active()->get();
      //  dd($rooms);
        return view('admin.manage-price.index', compact('pageTitle','rooms'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'code'          => 'required|unique:room_prices,code',
                'name'          => 'required|unique:room_prices,name',
                'price'         => 'required|numeric',
                'start_date'    => 'required|date',
                'end_date'      => 'required|date|after:start_date',
                'start_time'    => 'nullable|date_format:H:i',
                'end_time'      => 'nullable|date_format:H:i|after:start_time',
                'specific_date' => 'nullable|date',
            ],
            [
                'code.required'       => 'Mã bảng giá không được để trống!',
                'code.unique'         => 'Mã bảng giá đã được sử dụng!',
                'name.unique'         => 'Tên loại giá đã được sử dụng!',
                'name.required'       => 'Tên loại giá không được để trống!',
                'price.required'      => 'Giá không được để trống!',
                'price.numeric'       => 'Giá không đúng định dạng!',
                'start_date.required' => 'Ngày bắt đầu không được để trống!',
                'start_date.date'     => 'Ngày bắt đầu không đúng định dạng!',
                'end_date.required'   => 'Ngày kết thúc không được để trống!',
                'end_date.date'       => 'Ngày kết thúc không đúng định dạng!',
                'end_date.after'      => 'Ngày kết thúc phải lớn hơn ngày bắt đầu!',
                'start_time.date_format' => 'Thời gian bắt đầu không đúng định dạng H:i.',
                'end_time.date_format' => 'Thời gian kết thúc không đúng định dạng H:i.',
                'end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
                'specific_date.date'  => 'Ngày đặc biệt không đúng định dạng!',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'key' => $validator->errors()->keys()[0],
            ]);
        }

        $data = $validator->validated();
        // Chuyển đổi status thành active/inactive dựa trên giá trị gửi lên
        $data['status'] = $request->status == "on" ? 'active' : 'inactive';

        RoomPrice::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Thao tác thành công!'
        ]);
    }

    public function edit(string $id)
    {

        $price = RoomPrice::query()->find($id);

        if (! $price) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không tồn tại trên hệ thống!'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $price
        ]);
    }

    public function update(Request $request, $id)
    {
        $price = RoomPrice::query()->find($id);

        if (! $price) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không tồn tại trên hệ thống!'
            ]);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'code' => 'required|unique:room_prices,code,' . $id,
                'name' => 'required|unique:room_prices,name,' . $id,
                'price' => 'required|numeric',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ],
            [
                'code.required' => 'Mã bảng giá không được để trống!',
                'code.unique' => 'Mã bảng giá đã được sử dụng!',
                'name.unique' => 'Tên loại giá đã được sử dụng!',
                'name.required' => 'Tên loại giá không được để trống!',
                'price.required' => 'Giá không được để trống!',
                'price.numeric' => 'Giá không đúng định dạng!',
                'start_date.required' => 'Ngày bắt đầu không được để trống!',
                'start_date.date' => 'Ngày bắt đầu không đúng định dạng!',
                'end_date.required' => 'Ngày kết thúc không được để trống!',
                'end_date.date' => 'Ngày kết thúc không đúng định dạng!',
                'end_date.after' => 'Ngày kết thúc phải lớn hơn ngày bắt đầu!',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'key' => $validator->errors()->keys()[0],
            ]);
        }

        $data = $validator->validated();

        // Chuyển đổi status này active/inactive dựa trên giá trị gửi lên
        $data['status'] = $request->status == "on" ? 'active' : 'inactive';

        $price->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Thao tác này thành công!'
        ]);
    }


    public function updateStatus($id)
    {

        $price = RoomPrice::query()->find($id);

        if (! $price) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không tồn tại trên hệ thống!'
            ]);
        }

        $price->status = $price->status == 'active' ? 'inactive' : 'active';
        $price->save();

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật trạng thái thành công.'
        ]);
    }

    public function destroy($id)
    {
        $price = RoomPrice::find($id);

        if (! $price) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không tồn tại trên hệ thống!'
            ]);
        }

        $price->delete();
        return response()->json([
            'status' => true,
            'message' => 'Thao tác thành công.'
        ]);
    }
}
