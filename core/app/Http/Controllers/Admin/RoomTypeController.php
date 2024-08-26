<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomType;
use App\Models\Amenity;
use App\Models\BedType;
use App\Models\Facility;
use App\Models\RoomTypeImage;
use App\Models\Room;
use App\Rules\FileTypeValidate;

class RoomTypeController extends Controller
{
    public function index()
    {
        $pageTitle   = 'All Room Types';
        $typeList    = RoomType::with('amenities', 'facilities')->withCount('rooms')->latest()->paginate(getPaginate());
        return view('admin.hotel.room_type.list', compact('pageTitle', 'typeList'));
    }

    public function create()
    {
        $pageTitle   = 'Add Room Type';
        $amenities   = Amenity::active()->get();
        $facilities  = Facility::active()->get();
        $bedTypes    = BedType::all();
        return view('admin.hotel.room_type.create', compact('pageTitle', 'amenities', 'facilities', 'bedTypes'));
    }

    public function edit($id)
    {
        $roomType    = RoomType::with('amenities', 'facilities', 'rooms', 'images')->findOrFail($id);
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

        return view('admin.hotel.room_type.create', compact('pageTitle', 'roomType', 'amenities', 'facilities', 'bedTypes', 'images'));
    }

    public function save(Request $request, $id = 0)
    {
        $this->validation($request, $id);

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
            $notification     = 'Room type updated successfully';
        } else {
            $roomType         = new RoomType();
            $notification     = 'Room type added successfully';
        }

        $roomType->name                = $request->name;
        $roomType->slug                = $request->slug;
        $roomType->total_adult         = $request->total_adult;
        $roomType->total_child         = $request->total_child;
        $roomType->fare                = $request->fare;
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

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
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
            'slug'                => 'required|unique:room_types,slug,' . $id
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
