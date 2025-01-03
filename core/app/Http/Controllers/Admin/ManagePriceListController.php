<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomPrice;
use App\Models\RoomType;
use App\Models\RoomTypePrice;
use App\Models\SetupPricing;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

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
        return view('admin.manage-price.index', compact('pageTitle', 'rooms', 'input','setupPrice'));
    }   
    //add 
    public function addPrice(Request $request){
        $validatedData = $request->validate([
            'room_type_id' => 'required|exists:room_types,id', 
            'setup_pricing_id' => 'required|exists:setup_pricing,id', 
        ], [
            // Custom thông báo lỗi
            'room_type_id.required' => 'Vui lòng chọn mã hạng phòng.',
            'room_type_id.exists' => 'Mã hạng phòng không tồn tại.',
            'setup_pricing_id.required' => 'Vui lòng chọn mã giá.',
            'setup_pricing_id.exists' => 'Mã giá không tồn tại.',
        ]);
        try {
            $exists = RoomTypePrice::where('room_type_id', $validatedData['room_type_id'])
            ->where('setup_pricing_id', $validatedData['setup_pricing_id'])
            ->exists();
            if ($exists) {
              
                $notify[] = ['error', 'Dữ liệu đã tồn tại.'];
                return back()->withNotify($notify);
            }
            RoomTypePrice::create([
                'room_type_id' => $validatedData['room_type_id'],
                'setup_pricing_id' => $validatedData['setup_pricing_id'],
                'unit_code' => hf('ma_coso'),
            ]);
            $notify[] = ['success', 'Thêm thành công'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            // Trường hợp có lỗi xảy ra
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }
    // show
    public function showRoomTypePrice(){
        $setupPrice = RoomTypePrice::with('setupPricing','roomType')->get();
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
    public function setupPriceRoomType(){
        $setupPrice = SetupPricing::all();
        return response()->json([
            'status'  => 'success',
            'data'    => $setupPrice,
            'message' => 'Thêm giá phòng thành công.',
        ]);
    }
    // delete a setup price
    public function deletePriceRoomType($id){
        $priceRoomType = SetupPricing::find($id);
        if($priceRoomType){
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
    
    public function addPriceRoomType(Request $request)
    { 
        $validatedData = $request->validate([
            'price_code' => 'required',
            'price_name' => 'required|unique:setup_pricing',
            'description' => 'required',
            'check_in_time' => 'required',
            'check_out_time' => 'required',
            'round_time' => 'required',
            'price_requirement' => 'required',
        ], 
        [
            'price_name.unique' => 'Tên giá không được trùng lặp', 
        ]);
        try {
           
            DB::beginTransaction(); 
            $priceRoomType = new SetupPricing();
            $priceRoomType->price_code           = $validatedData['price_code'];
            $priceRoomType->price_name           = $validatedData['price_name'];
            $priceRoomType->description          = $validatedData['description'];
            $priceRoomType->check_in_time        = $validatedData['check_in_time'];
            $priceRoomType->check_out_time       = $validatedData['check_out_time'];
            $priceRoomType->round_time           = $validatedData['round_time'];
            $priceRoomType->unit_code            = hf('ma_coso');
            if (is_array($validatedData['price_requirement'])) {
                // Loại bỏ các giá trị null trong mảng
                $priceRequirements = array_filter($validatedData['price_requirement'], function($value) {
                    return $value !== null;
                });
            
                // Chuyển đổi mảng thành chuỗi JSON sau khi loại bỏ giá trị null
                $priceRoomType->price_requirement = json_encode($priceRequirements);
            } else {
                $priceRoomType->price_requirement = $validatedData['price_requirement'];
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
    public function modalAdd(){

        $content = View::make('admin/manage-price-room-type/modal-add-price-room-type')->render();
        return response()->json(['content' => $content]);
    }
    public function modalEdit($id) {
        $pricing = SetupPricing::find($id);
        $content = View::make('admin/manage-price-room-type/modal-edit-price-room-type', ['pricing' => $pricing])->render();
        return response()->json(['content' => $content]);
    }
    //edit settings
    public function editPriceRoomType(Request $request, $id) {
        $validatedData = $request->validate([
            'price_code' => 'required',
            'price_name' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $existingName = $request->input('price_name');
                    $existingPrice = SetupPricing::where('price_name', $existingName)->first();
                    if ($existingPrice && $existingPrice->id != $request->route('id')) {
                        $fail('Tên giá không được trùng lặp');
                    }
                }
            ],
            'description'       => 'required',
            'check_in_time'     => 'required',
            'check_out_time'    => 'required',
            'round_time'        => 'required',
            'price_requirement' => 'required',
        ]
       );
        try {
           
            DB::beginTransaction(); 
            $priceRoomType                       = SetupPricing::find($id);
            $priceRoomType->price_code           = $validatedData['price_code'];
            $priceRoomType->price_name           = $validatedData['price_name'];
            $priceRoomType->description          = $validatedData['description'];
            $priceRoomType->check_in_time        = $validatedData['check_in_time'];
            $priceRoomType->check_out_time       = $validatedData['check_out_time'];
            $priceRoomType->round_time           = $validatedData['round_time'];
            $priceRoomType->unit_code            = hf('ma_coso');
            if (is_array($validatedData['price_requirement'])) {
                // Loại bỏ các giá trị null trong mảng
                $priceRequirements = array_filter($validatedData['price_requirement'], function($value) {
                    return $value !== null;
                });
            
                // Chuyển đổi mảng thành chuỗi JSON sau khi loại bỏ giá trị null
                $priceRoomType->price_requirement = json_encode($priceRequirements);
            } else {
                $priceRoomType->price_requirement = $validatedData['price_requirement'];
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
    public function updateRoomTypePrice(Request $request) {
        // switch case
        $case = $request->method;
        switch ($case) {
            case 'unit_price':
                return  $this->configUnitPrice($request->id, $request->value);
                break;
            case 'overtime_price':
                return  $this->configOvertimePrice($request->id, $request->value);
                break;
            case 'extra_person_price':
                return  $this->configExtraPersonPrice($request->id, $request->value);
                break;
            case 'price_validity_period':
                return  $this->configPriceValidityPeriod($request->id, $request->value);
                break;
            default:
                return response()->json(['error' => 'Thao tác không hợp lệ.']);
        }
    }
    private  function configPriceValidityPeriod($id, $value){
        $priceRoomType = RoomTypePrice::find($id);
        if($priceRoomType){
            $priceRoomType->price_validity_period = $value;
            $priceRoomType->save();
            return response()->json([
               'status'  =>'success',
               'message' => 'Cập nhật giá phòng thành công.',
            ]);
        }
        return response()->json([
           'status'  => 'error',
           'message' => 'Giá phòng không tồn tại.',
        ]);
    }
    private  function configUnitPrice($id, $value){
        $priceRoomType = RoomTypePrice::find($id);
        if($priceRoomType){
            $priceRoomType->unit_price = $value;
            $priceRoomType->save();
            return response()->json([
               'status'  =>'success',
               'message' => 'Cập nhật giá phòng thành công.',
            ]);
        }
        return response()->json([
           'status'  => 'error',
           'message' => 'Giá phòng không tồn tại.',
        ]);
    }
    private  function configOvertimePrice($id, $value){
        $priceRoomType = RoomTypePrice::find($id);
        if($priceRoomType){
            $priceRoomType->overtime_price = $value;
            $priceRoomType->save();
            return response()->json([
               'status'  =>'success',
               'message' => 'Cập nhật giá phòng thành công.',
            ]);
        }
        return response()->json([
           'status'  => 'error',
           'message' => 'Giá phòng không tồn tại.',
        ]);
    }
    private  function configExtraPersonPrice($id, $value){
        $priceRoomType = RoomTypePrice::find($id);
        if($priceRoomType){
            $priceRoomType->extra_person_price = $value;
            $priceRoomType->save();
            return response()->json([
               'status'  =>'success',
               'message' => 'Cập nhật giá phòng thành công.',
            ]);
        }
        return response()->json([
           'status'  => 'error',
           'message' => 'Giá phòng không tồn tại.',
        ]);
    }
    //delete RoomTypePrice
    public function deleteRoomTypePrice($id){
        $priceRoomType = RoomTypePrice::find($id);
        if($priceRoomType){
            $priceRoomType->delete();
            return response()->json([
               'status'  =>'success',
               'message' => 'Xóa giá phòng thành công.',
            ]);
        }
        return response()->json([
           'status'  => 'error',
           'message' => 'Giá phòng không tồn tại.',
        ]);
    }
}
