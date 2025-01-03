<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Models\Booking;
use Log;
use App\Models\Room;
use App\Models\Amenity;
use App\Models\BedType;
use App\Models\Product;
use App\Models\Facility;
use App\Models\RoomType;
use App\Rules\StockCheck;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RoomTypeImage;
use App\Rules\FileTypeValidate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BookedRoom;
use App\Models\RoomImage;
use App\Models\RoomPrice;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Storage;


class RoomTypeController extends Controller
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new BaseRepository(new Room());
    }
    public function index()
    {
        $pageTitle   = 'Danh sách phòng';
        // $typeList    = Room::with('amenities', 'facilities', 'products')->latest()->paginate(getPaginate());
        // return view('admin.hotel.room_type.list', compact('pageTitle', 'typeList'));
        $search = request()->get('search');
        $perPage = request()->get('perPage', 10);
        $orderBy = request()->get('orderBy', 'id');
        $columns = [
            'id',
            'room_type_id',
            'room_number',
            'code',
            'status',
            'total_adult',
            'total_child',
            'created_at',
            'updated_at',
            'beds',
        ];
        $relations = [
            'amenities',
            'facilities',
            'products',
            'roomType'
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
                'results' => view('admin.table.manage-type-room', compact('response'))->render(),
                'pagination' => view('vendor.pagination.custom', compact('response'))->render(),
            ]);
        }
        return view('admin.hotel.room_type.list', compact('pageTitle'));
    }

    public function create()
    {
        $pageTitle   = 'Thêm loại phòng';
        $amenities   = Amenity::active()->get();
        $facilities  = Facility::active()->get();
        $bedTypes    = BedType::all();
        $roomTypes   = RoomType::pluck('name', 'id');
        $prices      = RoomPrice::active()->pluck('name', 'id');

        return view('admin.hotel.room_type.create', compact('pageTitle', 'amenities', 'facilities', 'bedTypes', 'roomTypes', 'prices'));
    }

    public function edit($id)
    {
        $roomType    = Room::with('amenities', 'facilities', 'images', 'products')->findOrFail($id);
        $pageTitle   = 'Cập nhật phòng  -' . $roomType->room_number;
        $amenities   = Amenity::active()->get();
        $facilities  = Facility::active()->get();
        $bedTypes    = BedType::all();
        $images      = [];
        $roomTypes   = RoomType::pluck('name', 'id');
        $prices = RoomPrice::active()->pluck('name', 'id');
        $selectedPrices = $roomType->prices()->pluck('id')->toArray();

        // dd($selectedPrices);

        foreach ($roomType->images as $key => $image) {
            $img['id']  = $image->id;
            $img['src'] = Storage::url($image->image);
            $images[]   = $img;
        }


        return view('admin.hotel.room_type.create', compact('pageTitle', 'roomType', 'amenities', 'facilities', 'bedTypes', 'images', 'roomTypes', 'prices', 'selectedPrices'));
    }

    public function save(Request $request, $id = 0)
    {
        $this->validation($request, $id);
        DB::beginTransaction();
        try {
            // if ($request->room_number) {
            //     $roomNumbers = Room::pluck('room_number')->toArray();
            //     $exists = in_array($request->room_number, $roomNumbers);
            //     if ($exists) {
            //         $notify[] = ['error', ' số phòng đã tồn tại'];
            //         return back()->withNotify($notify);
            //     }
            // }
            // $bedArray         = array_values($request->bed ?? []);
            $purifier         = new \HTMLPurifier();

            if ($id) {
                $room         = Room::findOrFail($id);

                $notification     = 'Đã cập nhật phòng thành công';
            } else {
                if ($request->room_number) {
                    $roomNumbers = Room::pluck('room_number')->toArray();
                    $exists = in_array($request->room_number, $roomNumbers);
                    if ($exists) {
                        $notify[] = ['error', ' Số phòng đã tồn tại'];
                        return back()->withNotify($notify);
                    }
                }
                $room             = new Room();

                $notification     = 'Đã thêm phòng thành công';
            }

            $room->code                = $request->code;
            $room->room_type_id        = $request->room_type_id;
            $room->room_number         = $request->room_number;
            $room->total_adult         = $request->total_adult;
            // $room->total_child         = $request->total_child;
            $room->description         = htmlspecialchars_decode($purifier->purify($request->description));
            $room->beds                = $request->beds;
            $room->is_featured         = $request->is_featured ? 1 : 0;
            //$room->cancellation_fee    = $request->cancellation_fee ?? 0;
            $room->cancellation_policy = htmlspecialchars_decode($purifier->purify($request->cancellation_policy));
            $room->is_clean            = Status::ROOM_CLEAN_ACTIVE;
            $room->status              = $request->status ? 1 : 0;

            if ($request->hasFile('main_image')) {
                $main_images = saveImages($request, 'main_image', 'roomImage', 600, 600);
                if ($room->main_image && Storage::disk('public')->exists($room->main_image)) {
                    Storage::disk('public')->delete($room->main_image);
                }
                $room->main_image = $main_images[0];
            }

            $room->save();

            // if ($request->prices) {
            //     $arr = [];
            //     foreach ($request->prices as  $value) {
            //         $data = RoomPrice::find($value);
            //         $arr[$value] = [
            //             'start_date' => $data->start_date,
            //             'end_date' => $data->end_date,
            //             'start_time' => $data->start_time,
            //             'end_time' => $data->end_time,
            //             'specific_date' => $data->specific_date
            //         ];
            //     }

            //     $room->prices()->sync($arr);
            // }


            // $insertRoomPrice = Room::where('room_number', $room->room_number)->first();
            // if ($insertRoomPrice) {
            //     $insertRoomPrice->updatePrices($request->input('prices'));
            // }

            // $room->amenities()->sync($request->amenities);
            // $room->facilities()->sync($request->facilities);

            // $this->insertProducts($request, $room);

            $this->removeImages($request, $room);

            $this->insertImages($request, $room);
            DB::commit();
            $notify[] = ['success', $notification];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            // dd([
            //     'message' => $e->getMessage(),
            // ]);
            DB::rollBack();
            FacadesLog::error($e->getMessage());
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    private function insertProducts($request, $room)
    {
        $data = [];

        // Lấy các sản phẩm hiện tại đã được liên kết với roomType để tăng lại tồn kho
        $currentProducts = $room->products()->pluck('quantity', 'product_id')->toArray();

        // Tăng lại số lượng sản phẩm vào kho cho các sản phẩm hiện tại
        foreach ($currentProducts as $productId => $quantity) {
            Product::find($productId)->increment('stock', $quantity);
        }

        // Kiểm tra xem phòng có tồn tại không
        if (!$room || !$room->id) {
            throw new \Exception('Room not found or invalid.');
        }

        // Tạo mảng rules cho việc validate số lượng sản phẩm
        $rules = [];

        if ($request->products) {

            // Validate và trừ đi số lượng sản phẩm mới
            foreach ($request->products as $productId => $quantity) {
                // Kiểm tra tồn kho bằng custom validation rule (StockCheck)
                $rules["products.$productId"] = ['required', 'integer', 'min:1', new StockCheck($productId)];

                // Trừ số lượng sản phẩm từ kho
                Product::find($productId)->decrement('stock', $quantity);
                $data[$productId] = ['quantity' => $quantity];
            }
        }


        // Thực hiện validate toàn bộ sản phẩm và số lượng
        $validated = $request->validate($rules);

        // Liên kết sản phẩm mới với room
        $room->products()->sync($data);
    }



    protected function validation($request, $id)
    {
        if ($id) {
            $imgValidation = ['nullable', new FileTypeValidate(['png', 'jpg', 'jpeg'])];
        } else {
            $imgValidation = ['required', new FileTypeValidate(['png', 'jpg', 'jpeg'])];
        }

        $request->validate([
            'code'                => 'string|max:6|unique:rooms,code,' . $id,
            'room_number'         => 'required|string|max:255',
            'total_adult'         => 'required|integer|gte:0',
            // 'total_child'         => 'required|integer|gte:0',
            'amenities'           => 'nullable|array',
            'amenities.*'         => 'integer|exists:amenities,id',
            'keywords'            => 'nullable|array',
            'keywords.*'          => 'string',
            'facilities'          => 'nullable|array',
            'facilities.*'        => 'integer|exists:facilities,id',
            // 'total_bed'           => 'required|gt:0',
            'main_image'          => $imgValidation,
            'beds'                 => 'required',
            // 'bed.*'               => 'exists:bed_types,name',
            'cancellation_policy' => 'nullable|string',
            'cancellation_fee'    => 'nullable|numeric',
            'products'            => 'nullable|array',
        ]);
        // |gte:0|lt:fare
    }

    protected function insertImages(Request $request, $roomType)
    {
        // Kiểm tra nếu có file hình ảnh được upload
        if ($request->hasFile('images')) {
            // Lưu hình ảnh vào folder `thumbnails`
            $thumbnails = saveImages($request, 'images', 'thumbnails', 400, 300);

            // Tạo mảng dữ liệu để thêm mới vào database
            $thumbnailsData = [];
            foreach ((array) $thumbnails as $thumbnail) {
                $thumbnailsData[] = ['image' => $thumbnail];
            }

            // dd($thumbnailsData);

            // Thêm dữ liệu vào bảng roomType_images
            $roomType->images()->createMany($thumbnailsData);
        }
    }


    protected function removeImages(Request $request, $roomType)
    {
        // Lấy danh sách ID hình ảnh cũ từ request->old
        $oldImages = $request->old ?? [];

        // Lấy tất cả hình ảnh hiện tại trong cơ sở dữ liệu
        $currentImages = $roomType->images->pluck('id')->toArray();

        // Tìm những hình ảnh không còn trong mảng old
        $imagesToRemove = array_diff($currentImages, $oldImages);

        // Xóa các hình ảnh không có trong mảng old
        foreach ($imagesToRemove as $imageId) {
            $roomImage = RoomImage::find($imageId);
            if ($roomImage) {
                // Xóa file hình ảnh trên hệ thống
                if (Storage::disk('public')->exists($roomImage->image)) {
                    Storage::disk('public')->delete($roomImage->image);
                }
                // Xóa record trong database
                $roomImage->delete();
            }
        }
    }


    public function status($id)
    {
        return Room::changeStatus($id);
    }

    public function checkSlug()
    {
        $exist = Room::where('id', '!=', request()->id)->where('slug', request()->slug)->exists();
        return response()->json([
            'exists' => $exist
        ]);
    }

    public function destroy($id)
    {
        $room = Room::find($id);

        if (! $room) {
            return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không tồn tại trên hệ thống!'
            ]);
        }

        if ($room->amenities()->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Không thể xóa vì phòng đang có tiện nghi.'
            ]);
        }

        if ($room->facilities()->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Không thể xóa vì phòng đang có cơ sở vật chất.'
            ]);
        }

        $bookings = BookedRoom::where('room_id', $id)->where('status', 1)->first();

        if ($bookings) {
            return response()->json([
                'status' => false,
                'message' => 'Không thể xóa vì phòng đang được thuệ.'
            ]);
        }
        $room->delete();
        return response()->json([
            'status' => true,
            'message' => 'Thao tác thành công.'
        ]);
    }

    public function  roomDeleted()
    {
        $pageTitle   = 'Danh sách các phòng đã xóa';
        $typeList    = Room::with('amenities', 'facilities', 'products')->onlyTrashed()->latest()->paginate(getPaginate());
        return view('admin.hotel.room_type.room-deleted', compact('pageTitle', 'typeList'));
    }
    public function restore($id)
    {
        $room = Room::withTrashed()->find($id);
        if (! $room) {
            $notification = 'Không tìm thấy dữ liệu';
            $notify[] = ['error', $notification];
            return back()->withNotify($notify);
        }

        $room->restore();
        $notification = 'Phòng đã được khôi phục thành công.';
        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }
}
