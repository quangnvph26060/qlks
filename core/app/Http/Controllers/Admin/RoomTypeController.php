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

class RoomTypeController extends Controller
{
    public function index()
    {
        $pageTitle   = 'Danh sách hạng phòng';
        $typeList    = RoomType::with('amenities', 'facilities')->withCount('rooms')->latest()->paginate(getPaginate());
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
            $img['src'] = getImage(getFilePath('roomTypeImage') . '/' . $image->image);
            $images[]   = $img;
        }

        // dd($roomType);

        return view('admin.hotel.room_type.create', compact('pageTitle', 'roomType', 'amenities', 'facilities', 'bedTypes', 'images'));
    }

    public function save(Request $request, $id = 0)
    {
        // dd($request->all());
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

            $roomType->name                = $request->name;
            $roomType->slug                = Str::slug($request->name);
            $roomType->total_adult         = $request->total_adult;
            $roomType->total_child         = $request->total_child;
            $roomType->fare                = $request->fare;
            $roomType->hourly_rate         = $request->hourly_rate;
            $roomType->seasonal_rate         = $request->seasonal_rate;
            $roomType->keywords            = $request->keywords ?? [];
            $roomType->description         = htmlspecialchars_decode($purifier->purify($request->description));
            $roomType->beds                = $bedArray;
            $roomType->is_featured         = $request->is_featured ? 1 : 0;
            $roomType->cancellation_fee    = $request->cancellation_fee ?? 0;
            $roomType->cancellation_policy = htmlspecialchars_decode($purifier->purify($request->cancellation_policy));


            if ($request->hasFile('main_image')) {
                $roomType->main_image = fileUploader($request->main_image, getFilePath('roomTypeImage'), getFileSize('roomTypeImage'), @$roomType->main_image, getThumbSize('roomTypeImage'));
            }


            $roomType->save();

            $roomType->amenities()->sync($request->amenities);
            $roomType->facilities()->sync($request->facilities);

            $this->insertImages($request, $roomType);

            $this->insertProducts($request, $roomType);

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

    protected function insertImages($request, $roomType)
    {
        $path = getFilePath('roomTypeImage');
        $this->removeImages($request, $roomType, $path);

        if ($request->hasFile('images')) {
            $size = getFileSize('roomTypeImage');
            $images = [];

            foreach ($request->file('images') as $file) {
                try {
                    $name = fileUploader($file, $path, $size);
                    $roomTypeImage        = new RoomTypeImage();
                    $roomTypeImage->image = $name;
                    $images[] = $roomTypeImage;
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Couldn\'t upload the logo'];
                    return back()->withNotify($notify);
                }
            }

            $roomType->images()->saveMany($images);
        }
    }

    protected function removeImages($request, $roomType, $path)
    {
        $previousImages = $roomType->images->pluck('id')->toArray();
        $imageToRemove  = array_values(array_diff($previousImages, $request->old ?? []));

        foreach ($imageToRemove as $item) {
            $roomImage   = RoomTypeImage::find($item);
            @unlink($path . '/' . $roomImage->image);
            $roomImage->delete();
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
