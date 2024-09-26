<?php

namespace App\Http\Controllers\Admin;

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
use Illuminate\Support\Facades\Storage;


class RoomTypeController extends Controller
{
    public function index()
    {
        $pageTitle   = 'Danh sách hạng phòng';
        $typeList    = RoomType::with('amenities', 'facilities', 'products')->withCount('rooms')->latest()->paginate(getPaginate());
        return view('admin.hotel.room_type.list', compact('pageTitle', 'typeList'));
    }

    public function create()
    {
        $pageTitle   = 'Thêm loại phòng';
        $amenities   = Amenity::active()->get();
        $facilities  = Facility::active()->get();
        $bedTypes    = BedType::all();
        return view('admin.hotel.room_type.create', compact('pageTitle', 'amenities', 'facilities', 'bedTypes'));
    }

    public function edit($id)
    {
        $roomType    = RoomType::with('amenities', 'facilities', 'rooms', 'images', 'products')->findOrFail($id);
        $pageTitle   = 'Update Room Type -' . $roomType->name;
        $amenities   = Amenity::active()->get();
        $facilities  = Facility::active()->get();
        $bedTypes    = BedType::all();
        $images      = [];

        foreach ($roomType->images as $key => $image) {
            $img['id']  = $image->id;
            $img['src'] = Storage::url($image->image);
            $images[]   = $img;
        }

        return view('admin.hotel.room_type.create', compact('pageTitle', 'roomType', 'amenities', 'facilities', 'bedTypes', 'images'));
    }

    public function save(Request $request, $id = 0)
    {
        $this->validation($request, $id);

        DB::beginTransaction();
        try {
            if ($request->room) {
                $roomNumbers = Room::pluck('room_number')->toArray();
                $exists = array_intersect($request->room, $roomNumbers);
                if (!empty($exists)) {
                    $notify[] = ['error', implode(', ', $exists) . ' room number already exists'];
                    return back()->withNotify($notify);
                }
            }
            $bedArray         = array_values($request->bed ?? []);
            $purifier         = new \HTMLPurifier();

            if ($id) {
                $roomType         = RoomType::findOrFail($id);
                $notification     = 'Đã cập nhật loại phòng thành công';
            } else {
                $roomType         = new RoomType();
                $notification     = 'Đã thêm loại phòng thành công';
            }

            $roomType->room_type_id        = $request->room_type_id;
            $roomType->name                = $request->name;
            $roomType->slug                = Str::slug($request->name);
            $roomType->total_adult         = $request->total_adult;
            $roomType->total_child         = $request->total_child;
            // $roomType->fare                = $request->fare;
            // $roomType->hourly_rate         = $request->hourly_rate;
            // $roomType->seasonal_rate         = $request->seasonal_rate;
            $roomType->keywords            = $request->keywords ?? [];
            $roomType->description         = htmlspecialchars_decode($purifier->purify($request->description));
            $roomType->beds                = $bedArray;
            $roomType->is_featured         = $request->is_featured ? 1 : 0;
            $roomType->cancellation_fee    = $request->cancellation_fee ?? 0;
            $roomType->cancellation_policy = htmlspecialchars_decode($purifier->purify($request->cancellation_policy));

            if ($request->hasFile('main_image')) {
                $main_images = saveImages($request, 'main_image', 'roomTypeImage', 1280, 720);
                if ($roomType->main_image && Storage::disk('public')->exists($roomType->main_image)) {
                    Storage::disk('public')->delete($roomType->main_image);
                }
                $roomType->main_image = $main_images[0];
            }

            $roomType->save();

            $roomType->amenities()->sync($request->amenities);
            $roomType->facilities()->sync($request->facilities);

            $this->insertProducts($request, $roomType);

            $this->removeImages($request, $roomType);

            $this->insertImages($request, $roomType);
            DB::commit();
            $notify[] = ['success', $notification];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            DB::rollBack();
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }   

    private function insertProducts($request, $roomType)
    {
        if ($request->products) {
            $data = [];

            // Lấy các sản phẩm hiện tại đã được liên kết với roomType để tăng lại tồn kho
            $currentProducts = $roomType->products()->pluck('quantity', 'product_id')->toArray();

            // Tăng lại số lượng sản phẩm vào kho cho các sản phẩm hiện tại
            foreach ($currentProducts as $productId => $quantity) {
                Product::find($productId)->increment('stock', $quantity);
            }

            // Tạo mảng rules cho việc validate số lượng sản phẩm
            $rules = [];

            // Validate và trừ đi số lượng sản phẩm mới
            foreach ($request->products as $productId => $quantity) {
                // Kiểm tra tồn kho bằng custom validation rule (StockCheck)
                $rules["products.$productId"] = ['required', 'integer', 'min:1', new StockCheck($productId)];

                // Kiểm tra nếu tồn kho hợp lệ thì trừ số lượng sản phẩm từ stock
                $data[$productId] = ['quantity' => $quantity];

                // Trừ số lượng sản phẩm từ kho
                Product::find($productId)->decrement('stock', $quantity);
            }

            // Thực hiện validate toàn bộ sản phẩm và số lượng
            $validated = $request->validate($rules);

            // Liên kết sản phẩm mới với roomType
            $roomType->products()->sync($data);
        }
    }



    protected function validation($request, $id)
    {
        if ($id) {
            $imgValidation = ['nullable', new FileTypeValidate(['png', 'jpg', 'jpeg'])];
        } else {
            $imgValidation = ['required', new FileTypeValidate(['png', 'jpg', 'jpeg'])];
        }

        $request->validate([
            'room_type_id'        => 'string|max:255|unique:room_types,room_type_id',
            'name'                => 'required|string|max:255|unique:room_types,name,' . $id,
            'total_adult'         => 'required|integer|gte:0',
            'total_child'         => 'required|integer|gte:0',
            'fare'                => 'required|gt:0',
            'amenities'           => 'nullable|array',
            'amenities.*'         => 'integer|exists:amenities,id',
            'keywords'            => 'nullable|array',
            'keywords.*'          => 'string',
            'facilities'          => 'nullable|array',
            'facilities.*'        => 'integer|exists:facilities,id',
            'total_bed'           => 'required|gt:0',
            'main_image'          => $imgValidation,
            'bed'                 => 'required|array',
            'bed.*'               => 'exists:bed_types,name',
            'cancellation_policy' => 'nullable|string',
            'cancellation_fee'    => 'nullable|numeric|gte:0|lt:fare',
            'products'            => 'nullable|array',
            // 'slug'                => 'required|unique:room_types,slug,' . $id
        ]);
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
            $roomImage = RoomTypeImage::find($imageId);
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
        return RoomType::changeStatus($id);
    }

    public function checkSlug()
    {
        $exist = RoomType::where('id', '!=', request()->id)->where('slug', request()->slug)->exists();
        return response()->json([
            'exists' => $exist
        ]);
    }
}
