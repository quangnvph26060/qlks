<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\HotelConfiguration;
use Illuminate\Http\Request;

class HotelConfigurationController extends Controller
{
 public function index()
    {
        $configs = HotelConfiguration::all();
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
            'logo' => 'nullable|image',
            'main_image' => 'nullable|image',
            'gallery_images.*' => 'nullable|image',
            'address' => 'required|string|max:255',
            'page_link' => 'nullable|url',
        ]);

        // Xử lý upload ảnh ở đây nếu có (logo, main_image, gallery_images)
        // Ví dụ lưu file, gán đường dẫn vào $data['logo'], ...

        HotelConfiguration::create($data);

        return redirect()->route('hotel_configurations.index')->with('success', 'Đã tạo cấu hình khách sạn.');
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
