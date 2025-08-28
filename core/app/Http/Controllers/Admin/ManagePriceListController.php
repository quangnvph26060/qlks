<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\RoomType;
use App\Models\RoomTypePrice;
use App\Models\SetupCode;
use App\Models\SetupPricing;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;

class ManagePriceListController extends Controller
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new RoomPrice());
    }

    public function priceList(Request $request)
    {
        $input = $request->name;
        $pageTitle = 'Danh sách giá loại phòng';
        $rooms = RoomType::query();
        $rooms = $rooms->active();
        if (!empty($input)) {
            $rooms->where('room_number', 'like', '%' . $input . '%');
        }
        $rooms = $rooms->paginate(getPaginate());
        $setupPrice = SetupPricing::all();
        return view('admin.manage-price.index', compact('pageTitle', 'rooms', 'input', 'setupPrice'));
    }
    //add
    public function addPrice(Request $request)
    {
        $validatedData = $request->validate([
            'room_type_id'          => 'required|exists:room_types,id',
            'setup_pricing_id'      => 'required|exists:setup_pricing,id',
            'unit_price'            => 'required',
            'overtime'              => 'required',
            'too_many_people'       => 'required',
            'price_validity_period' => 'required',
        ], [
            'room_type_id.required' => 'Vui lòng chọn mã loại phòng.',
            'room_type_id.exists'   => 'Mã loại phòng không tồn tại.',
            'setup_pricing_id.required' => 'Vui lòng chọn mã giá.',
            'setup_pricing_id.exists'   => 'Mã giá không tồn tại.',
        ]);
        try {
            $exists = RoomTypePrice::where('room_type_id', $validatedData['room_type_id'])
                ->where('setup_pricing_id', $validatedData['setup_pricing_id'])
                 ->where('unit_code', unitCode())
                 ->where('subdomain', subdomain())
                 ->where('price_validity_period', $validatedData['price_validity_period'])
                ->exists();
            if ($exists) {
                $notify[] = ['error', 'Dữ liệu đã tồn tại.'];
                return back()->withNotify($notify);
            }
            RoomTypePrice::create([
                'room_type_id'               => $validatedData['room_type_id'],
                'setup_pricing_id'           => $validatedData['setup_pricing_id'],
                'unit_price'                 => (float) str_replace('.', '', $validatedData['unit_price']),
                'overtime_price'             => (float) str_replace('.', '', $validatedData['overtime']),
                'extra_person_price'         => (float) str_replace('.', '', $validatedData['too_many_people']),
                'price_validity_period'      => $validatedData['price_validity_period'],
                'unit_code'                  => unitCode(),
                'subdomain'                  => subdomain(),
            ]);
            $notify[] = ['success', 'Thêm thành công'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            // Trường hợp có lỗi xảy ra
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }
    // show
    public function showRoomTypePrice()
    {
        $setupPrice = RoomTypePrice::with('setupPricing', 'roomType')->get();
        return response()->json([
            'status'  => 'success',
            'data'    => $setupPrice,
        ]);
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
    // cài đặt cách tính giá
    public function priceListRoomType(Request $request)
    {

        $input = $request->name;
        $pageTitle = 'Danh sách cài đặt tính giá';
        return view('admin.manage-price-room-type.index', compact('pageTitle', 'input'));
    }
    // get setup price
    public function setupPriceRoomType()
    {
        $setupPrice = SetupPricing::all();
        return response()->json([
            'status'  => 'success',
            'data'    => $setupPrice,
            'message' => 'Thêm giá phòng thành công.',
        ]);
    }
    // delete a setup price
    public function deletePriceRoomType($id)
    {
        $priceRoomType = SetupPricing::find($id);
        if (!$priceRoomType) {

            return response()->json([
                'status'  => 'error',
                'message' => 'Dữ liệu không tồn tại.',
            ]);
        }
        $usedByRoomType = RoomTypePrice::where('setup_pricing_id', $id)->exists();

        if ($usedByRoomType) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Không thể xóa cài đặt tính giá vì đang được sử dụng.',
            ]);
        }
        $priceRoomType->delete();
        return response()->json([
            'status'  => 'success',
            'message' => 'Xoá thành công.',
        ]);
    }

    public function addPriceRoomType(Request $request)
    {
        $request->validate([
            'price_code' => [
                'required',
                'regex:/^[A-Z0-9\-]+$/', // Chỉ cho phép chữ in hoa, số, dấu gạch ngang
                Rule::unique('setup_pricing', 'price_code')
                    ->where('unit_code', unitCode())
                    ->where('subdomain', subdomain()),
            ],
            'price_name' => [
                'required',
                'string',
                Rule::unique('setup_pricing', 'price_name')
                    ->where('unit_code', unitCode())
                    ->where('subdomain', subdomain()),
            ],
        ], [
            'price_code.required' => 'Mã giá không được để trống.',
            'price_code.regex' => 'Mã giá chỉ được gồm chữ in hoa, số và dấu gạch ngang.',
            'price_code.unique' => 'Mã giá đã tồn tại.',
            'price_name.required' => 'Tên giá không được để trống.',
            'price_name.unique' => 'Tên giá đã tồn tại.',
        ]);
        $validatedData = $request->all();
        try {

            DB::beginTransaction();

            $priceRoomType = new SetupPricing();
            $priceRoomType->price_code           = $validatedData['price_code'];
            $priceRoomType->price_name           = $validatedData['price_name'];
            $priceRoomType->description          = $validatedData['description'];
            $priceRoomType->check_in_time        = $validatedData['check_in_time'] ?? "";
            $priceRoomType->check_out_time       = $validatedData['check_out_time'] ?? "";
            $priceRoomType->round_time           = $validatedData['round_time'];
            $priceRoomType->unit_code            = unitCode();
            $priceRoomType->subdomain            = subdomain();
            if (!empty($validatedData['price_requirement'])) {
                $rawRequirements = $validatedData['price_requirement'];

                // Nếu là chuỗi kiểu "2025-06-06, 2025-06-07"
                if (is_string($rawRequirements)) {
                    $rawRequirements = explode(',', $rawRequirements);
                }

                // Nếu là mảng nhưng chứa 1 chuỗi bị gộp
                if (is_array($rawRequirements) && count($rawRequirements) === 1 && str_contains($rawRequirements[0], ',')) {
                    $rawRequirements = explode(',', $rawRequirements[0]);
                }

                $priceRequirements = collect($rawRequirements)
                    ->map(fn($v) => trim($v))
                    ->filter()
                    ->values()
                    ->all();

                $priceRoomType->price_requirement = json_encode($priceRequirements);
            } else {
                $priceRoomType->price_requirement = json_encode([]);
            }

            $priceRoomType->save();
            DB::commit();

            $notify[] = ['success', 'Thêm thành công'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi khi thêm giá phòng: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Đã xảy ra lỗi khi thêm giá phòng. Vui lòng thử lại.']);
        }
    }
    //modal
    public function modalAdd()
    {
        $count = SetupPricing::count();
        $code = SetupCode::where('menu_name', 'Cài đặt tính giá')->value('code');
        $code = $code ? $code . $count + 1 : '';
        // $content = View::make('admin/manage-price-room-type/modal-add-price-room-type')->render();
        $content = View::make('admin/manage-price-room-type/modal-add-price-room-type', [
            'code' => $code
        ])->render();
        return response()->json(['content' => $content]);
    }
    public function modalEdit($id)
    {
        $pricing = SetupPricing::find($id);
        $content = View::make('admin/manage-price-room-type/modal-edit-price-room-type', ['pricing' => $pricing])->render();
        return response()->json(['content' => $content]);
    }
    //edit settings
    public function editPriceRoomType(Request $request, $id)
    {
        $request->validate([
            'price_code' => [
                'required',
                'regex:/^[A-Z0-9\-]+$/',
                Rule::unique('setup_pricing', 'price_code')
                    ->ignore($id)
                    ->where('unit_code', unitCode())
                    ->where('subdomain', subdomain()),
            ],
            'price_name' => [
                'required',
                'string',
                Rule::unique('setup_pricing', 'price_name')
                    ->ignore($id)
                    ->where('unit_code', unitCode())
                    ->where('subdomain', subdomain()),
            ],
        ], [
            'price_code.required' => 'Mã giá không được để trống.',
            'price_code.regex' => 'Mã giá chỉ được gồm chữ in hoa, số và dấu gạch ngang.',
            'price_code.unique' => 'Mã giá đã tồn tại.',
            'price_name.required' => 'Tên giá không được để trống.',
            'price_name.unique' => 'Tên giá đã tồn tại.',
        ]);

        $validatedData = $request->all();
        try {

            DB::beginTransaction();
            $priceRoomType                       = SetupPricing::find($id);
            $priceRoomType->price_code           = $validatedData['price_code'];
            $priceRoomType->price_name           = $validatedData['price_name'];
            $priceRoomType->description          = $validatedData['description'];
            $priceRoomType->check_in_time        = $validatedData['check_in_time'] ?? "";
            $priceRoomType->check_out_time       = $validatedData['check_out_time'] ?? "";
            $priceRoomType->round_time           = $validatedData['round_time'];
           if (!empty($validatedData['price_requirement'])) {
            $rawRequirements = $validatedData['price_requirement'];

            // Normalize input to array
            if (is_string($rawRequirements)) {
                $rawRequirements = [$rawRequirements];
            }

            $priceRequirements = collect($rawRequirements)
                ->flatMap(function ($item) {
                    return str_contains($item, ',') ? explode(',', $item) : [$item];
                })
                ->map(fn($v) => trim($v))
                ->filter()
                ->values()
                ->all();

            $priceRoomType->price_requirement = json_encode($priceRequirements);
        } else {
            $priceRoomType->price_requirement = json_encode([]);
        }
            $priceRoomType->save();
            DB::commit();

            $notify[] = ['success', 'Cập nhật thành công'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Lỗi khi thêm giá phòng: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Đã xảy ra lỗi khi thêm giá phòng. Vui lòng thử lại.']);
        }
    }
    // updateRoomTypePrice
    public function updateRoomTypePrice($id, Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'room_type_id'          => 'required|exists:room_types,id',
            'setup_pricing_id'      => 'required|exists:setup_pricing,id',
            'unit_price'            => 'required',
            'overtime'              => 'required',
            'too_many_people'       => 'required',
            'price_validity_period' => 'required',
        ], [
            'room_type_id.required' => 'Vui lòng chọn mã loại phòng.',
            'room_type_id.exists'   => 'Mã loại phòng không tồn tại.',
            'setup_pricing_id.required' => 'Vui lòng chọn mã giá.',
            'setup_pricing_id.exists'   => 'Mã giá không tồn tại.',
        ]);


        $priceRoomType = RoomTypePrice::find($id);
        if (!$priceRoomType) {
            $notify[] = ['error', 'Không tìm thấy dữ liệu'];
            return back()->withNotify($notify);
        }

        $isDuplicate = RoomTypePrice::where('room_type_id', $validatedData['room_type_id'])
            ->where('setup_pricing_id', $validatedData['setup_pricing_id'])
            // ->where('price_validity_period', $validatedData['price_validity_period'])
            // ->where('unit_code', unitCode())
            // ->where('subdomain', subdomain())
            ->where('id', '!=', $id) // Loại trừ bản ghi hiện tại
            ->exists();

        if ($isDuplicate) {
            $notify[] = ['error', 'Cặp mã loại phòng và mã giá này đã tồn tại.'];
            return back()->withNotify($notify)->withInput();
        }
        $priceRoomType->update([
            'room_type_id'          => $validatedData['room_type_id'],
            'setup_pricing_id'      => $validatedData['setup_pricing_id'],
            'unit_price'            => (float) str_replace('.', '', $validatedData['unit_price']),
            'overtime_price'        => (float) str_replace('.', '', $validatedData['overtime'] ?? '0'),
            'extra_person_price'    => (float) str_replace('.', '', $validatedData['too_many_people'] ?? '0'),
            'price_validity_period' => $validatedData['price_validity_period'],
            'unit_code'             =>  unitCode(),
        ]);

        $notify[] = ['success', 'Cập nhật thành công'];
        return back()->withNotify($notify);
    }

    //delete RoomTypePrice
    public function deleteRoomTypePrice($id)
    {
        $priceRoomType = RoomTypePrice::find($id);
        if ($priceRoomType) {
            $priceRoomType->delete();
            return response()->json([
                'status'  => 'success',
                'message' => 'Xóa giá phòng thành công.',
            ]);
        }
        return response()->json([
            'status'  => 'error',
            'message' => 'Giá phòng không tồn tại.',
        ]);
    }
    public function findRooomType(Request $request)
    {
        $id = $request->id;
        $priceRoomType = RoomTypePrice::where('id', $id)->where('unit_code', unitCode())->with('setupPricing', 'roomType')->first();
        if (!$priceRoomType) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy giá phòng.'
            ]);
        }
        return response()->json([
            'status' => 'success',
            'data'   => $priceRoomType,
        ]);
    }
}
