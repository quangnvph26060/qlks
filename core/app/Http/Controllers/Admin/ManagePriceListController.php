<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DayOfWeek;
use App\Models\HolidayPrice;
use App\Models\PriceType;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ManagePriceListController extends Controller
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new Room());
    }

    public function priceList()
    {


        $pageTitle = 'Danh sách bảng giá';
        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        $columns = [
            'id',
            'room_type_id',
            'room_number',
            'code',
            'status',
            'created_at',
            'updated_at',
        ];
        $relations = [
            'amenities',
            'facilities',
            'products',
            'roomPrices'
        ];
        $searchColumns = [
            'code',
            'room_number',
        ];
        $relationSearchColumns = ['roomType' => ['name']];

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
        $rooms = Room::select('id', 'code', 'room_number')->get();
        $priceTypes = PriceType::all();
        $daysOfWeek = DayOfWeek::all();
        return view('admin.manage-price.index', compact('pageTitle', 'rooms', 'priceTypes', 'daysOfWeek'));
    }

    public function store(Request $request)
    {

        $validator = $this->validationPrice($request);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'key' => $validator->errors()->keys()[0],
            ]);
        }
        $data = $validator->validated();
        $existingRecord = DB::table('rooms_prices')
            ->where('room_id', $data['room_id'])
            ->where('price_type_id', $data['price_type_id'])
            ->where('day_of_week_id', $data['day_of_week_id'])
            ->first();
        if ($existingRecord) {
            return response()->json([
                'status' => false,
                'message' => 'Cặp phòng, loại giá và ngày trong tuần này đã tồn tại'
            ]);
        }
        if (!empty($data['holiday_date'])) {
            $data['holiday_date'] = Carbon::parse($data['holiday_date']);
            HolidayPrice::create($data);
        } else {
            RoomPrice::create($data);
        }
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

    protected function validationPrice($request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'room_id' => 'required|exists:rooms,id',
                'price_type_id' => 'required|exists:price_types,id',
                'day_of_week_id' => 'nullable|exists:days_of_week,id',
                'holiday_date' => 'nullable|date',
                'first_hour' => 'required|numeric',
                'additional_hour' => 'required|numeric',
                'full_day' => 'required|numeric',
                'overnight' => 'required|numeric',
                'room_price_unique' => 'unique:rooms_prices,room_id,price_type_id,day_of_week_id',
            ],
            [
                'room_id.required' => 'Chọn phòng không được trống',
                'room_id.exists' => 'Phòng đã chọn không tồn tại',
                'price_type_id.required' => 'Chọn loại giá không được trống',
                'price_type_id.exists' => 'Loại giá đã chọn không tồn tại',
                'day_of_week_id.exists' => 'Ngày trong tuần không tồn tại',
                'holiday_date.date' => 'Ngày lễ không hợp lệ',
                'first_hour.required' => 'Giá giờ đầu tiên không được trống',
                'first_hour.numeric' => 'Giá giờ đầu tiên phải là số',
                'additional_hour.required' => 'Giá giờ thêm không được trống',
                'additional_hour.numeric' => 'Giá giờ thêm phải là số',
                'full_day.required' => 'Giá cả ngày không được trống',
                'full_day.numeric' => 'Giá cả ngày phải là số',
                'overnight.required' => 'Giá qua đêm không được trống',
                'overnight.numeric' => 'Giá qua đêm phải là số',
                'room_price_unique' => 'Cặp phòng, loại giá và ngày trong tuần đã tồn tại.',
            ]
        );
        return $validator;
    }
}
