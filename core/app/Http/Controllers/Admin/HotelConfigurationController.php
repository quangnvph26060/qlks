<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HotelConfiguration;
use App\Models\HotelFacility;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class HotelConfigurationController extends Controller
{
    public function index()
    {
        $hotelActive = HotelFacility::where('subdomain', subdomain())
                ->where('ma_coso', unitCode())
                ->firstOrFail();
        $configs = HotelConfiguration::with('hotelFacility.galleryImages')
        ->where('hotel_facility_id',$hotelActive->id)->first();
        return view('admin.setting.hotel_configurations', compact('configs'));
    }

    public function create()
    {
        return view('hotel_configurations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'hotel_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:255',
            'external_link' => 'nullable|url',
            'logo' => 'nullable|image',
            'main_image' => 'nullable|image',
            'gallery_images.*' => 'nullable|image',
            'province'=> 'nullable|string',
            'email'=> 'string',
        ]);

        try {
            // Lấy HotelFacility hiện tại
            $hotelActive = HotelFacility::where('subdomain', subdomain())
                ->where('ma_coso', unitCode())
                ->firstOrFail();

            $data['hotel_facility_id'] = $hotelActive->id;

            // Xử lý logo
            if ($request->hasFile('logo')) {
                $logo = saveImages($request, 'logo', 'hotel/logo', 200, 200);
                if (!empty($logo)) {
                    $data['logo'] = $logo[0];
                }
            }

            // Xử lý main_image
            if ($request->hasFile('main_image')) {
                $mainImage = saveImages($request, 'main_image', 'hotel/main_image', 600, 600);
                if (!empty($mainImage)) {
                    $data['main_image'] = $mainImage[0];
                }
            }

            // Lưu icon
            if ($request->hasFile('icon')) {
                $icon = saveImages($request, 'icon', 'hotel/icon', 100, 100);
                if (!empty($icon)) {
                    $data['icon'] = $icon[0];
                }
            }
            $data['slug'] = Str::slug($data['hotel_name']);
            $data['latitude'] = $request->latitude;
            $data['longitude'] = $request->longitude;
              $data['chinh_sach'] = $request->chinh_sach;
                $data['gioi_thieu'] = $request->gioi_thieu;
                $data['province_code'] = $request->province_code;
            // Kiểm tra đã có hotel_configuration chưa
            $existingHotel = HotelConfiguration::where('hotel_facility_id', $hotelActive->id)->first();

            if ($existingHotel) {
                // Nếu đã có → update
                $existingHotel->update($data);
                $hotel = $existingHotel;
            } else {
                // Nếu chưa có → tạo mới
                $hotel = HotelConfiguration::create($data);
            }

            // Xử lý nhiều ảnh gallery
            if ($request->hasFile('gallery_images')) {
                $galleryImages = saveImages($request, 'gallery_images', 'hotel/gallery', 800, 600);

                // Nếu là update, có thể xóa ảnh cũ nếu cần (tuỳ yêu cầu bạn)
                // \DB::table('hotel_gallery_images')->where('hotel_facility_id', $hotel->id)->delete();

                foreach ($galleryImages as $imagePath) {
                    \DB::table('hotel_gallery_images')->insert([
                        'hotel_facility_id' => $hotel->hotel_facility_id,
                        'image_url' => $imagePath,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            $notify[] = ['success', 'Cấu hình khách sạn đã được lưu.'];
            return back()->withNotify($notify);
        } catch (\Exception $e) {
            \Log::error('Lỗi lưu cấu hình khách sạn: ' . $e->getMessage());
            return back()->with('error', 'Đã xảy ra lỗi khi lưu cấu hình khách sạn.');
        }
    }



    public function show(HotelConfiguration $hotelConfiguration)
    {
        return view('hotel_configurations.show', compact('hotelConfiguration'));
    }

    public function edit(HotelConfiguration $hotelConfiguration)
    {
        return view('hotel_configurations.edit', compact('hotelConfiguration'));
    }

    public function update(Request $request, HotelConfiguration $hotelConfiguration)
    {
        $data = $request->validate([
            'hotel_name' => 'required|string|max:255',
            'logo' => 'nullable|image',
            'main_image' => 'nullable|image',
            'gallery_images.*' => 'nullable|image',
            'address' => 'required|string|max:255',
            'page_link' => 'nullable|url',
        ]);

        // Xử lý upload ảnh, cập nhật tương tự store()

        $hotelConfiguration->update($data);

        return redirect()->route('hotel_configurations.index')->with('success', 'Đã cập nhật cấu hình khách sạn.');
    }

    public function destroy(HotelConfiguration $hotelConfiguration)
    {
        $hotelConfiguration->delete();

        return redirect()->route('hotel_configurations.index')->with('success', 'Đã xóa cấu hình khách sạn.');
    }
}
