<?php

namespace App\Http\Controllers\Admin;

use App\Models\Amenity;
use App\Models\RoomImage;
use App\Models\RoomTypeAmenity;
use App\Models\RoomTypePrice;
use App\Models\SetupPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use App\Models\HotelConfiguration;
use App\Models\Room;
use App\Models\RoomDirection;
use App\Models\RoomType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
class TravelVietController extends Controller
{
    public function index()
    {
        return view('admin.travelviet.index');
    }

    /**
     * Helper method to make API calls to TravelViet
     */
    private function makeTravelVietApiCall($endpoint, $langId, $token, $operation = '')
    {
        if (empty($token)) {
            Log::error('TravelViet API: Missing TRAVELVIET_TOKEN in environment');
            return response()->json([
                'message' => 'Cấu hình API chưa đầy đủ. Vui lòng liên hệ quản trị viên để cấu hình TRAVELVIET_TOKEN.',
                'error_code' => 'MISSING_TOKEN'
            ], 500);
        }

        $baseUrl = rtrim(env('TRAVELVIET_BASE_URL', 'http://127.0.0.1:8000'), '/');
        $url = $baseUrl . "/api/{$langId}/{$endpoint}/";

        Log::info("TravelViet API Request{$operation}", [
            'url' => $url,
            'lang_id' => $langId
        ]);

        try {
            $response = Http::withToken($token) // Gửi token từ DB
                ->acceptJson()
                ->timeout(30)
                ->get($url);

            if (!$response->ok()) {
                Log::error("TravelViet API Error{$operation}", [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'url' => $url
                ]);

                $errorMessage = 'Không thể kết nối đến API TravelViet';
                if ($response->status() === 400) {
                    $responseData = $response->json();
                    $errorMessage = 'Khách sạn không tồn tại bên TravelViet';
                } elseif ($response->status() === 401) {
                    $errorMessage = 'Token API không hợp lệ hoặc đã hết hạn';
                } elseif ($response->status() === 404) {
                    $errorMessage = 'API endpoint không tồn tại';
                } elseif ($response->status() >= 500) {
                    $errorMessage = 'Lỗi server API TravelViet';
                }

                return response()->json([
                    'message' => $errorMessage,
                    'status' => $response->status(),
                    'error_code' => 'API_ERROR'
                ], 502);
            }

            $data = $response->json();
            Log::info("TravelViet API Success{$operation}", [
                'data_count' => is_array($data) ? count($data) : 'unknown',
                'data' => $data,
                'url' => $url,
                'lang_id' => $langId
            ]);

            return response()->json([
                'ok' => true,
                'data' => $data,
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("TravelViet API Connection Error{$operation}", [
                'error' => $e->getMessage(),
                'url' => $url
            ]);
            return response()->json([
                'message' => 'Không thể kết nối đến server API TravelViet. Vui lòng kiểm tra kết nối mạng hoặc liên hệ quản trị viên.',
                'error_code' => 'CONNECTION_ERROR'
            ], 500);
        } catch (\Throwable $e) {
            Log::error("TravelViet API Unexpected Error{$operation}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Có lỗi xảy ra khi gọi API TravelViet: ' . $e->getMessage(),
                'error_code' => 'UNEXPECTED_ERROR'
            ], 500);
        }
    }
    public function randomToken(Request $request)
    {
        try {
            $id = $request->id;

            if (!$id) {
                return response()->json(['success' => false, 'message' => 'Thiếu ID']);
            }

            // Tạo random token 10 ký tự
            $token = Str::random(30);

            // Lưu vào DB
            HotelConfiguration::where('id', $id)->update(['bearer_token' => $token]);

            Log::info('Random bearer token', ['id' => $id, 'token' => $token]);

            return response()->json(['success' => true, 'token' => $token]);
        } catch (\Throwable $e) {
            Log::error('Random Token Error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Lỗi hệ thống']);
        }
    }
    public function searchHotel(Request $request)
    {
        $request->validate([
            'lang_id' => 'required',
            'bearer_token' => 'required|string',
        ]);

        $langId = $request->input('lang_id');
        $hotelToken = $request->input('bearer_token');

        return $this->makeTravelVietApiCall('khach-san/thong-tin-khach-san', $langId, $hotelToken, ' - Hotel Search');
    }

    public function searchRoomTypes(Request $request)
    {
        $request->validate([
            'lang_id' => 'required',
            'bearer_token' => 'required|string',
        ]);
        $langId = $request->input('lang_id');
          $hotelToken = $request->input('bearer_token');

        return $this->makeTravelVietApiCall('khach-san/danh-muc-phong', $langId, $hotelToken, ' - Room Types');
    }

    public function searchRooms(Request $request)
    {
        $request->validate([
            'lang_id' => 'required',
            'bearer_token' => 'required|string',
        ]);

        $langId = $request->input('lang_id');
           $hotelToken = $request->input('bearer_token');

        return $this->makeTravelVietApiCall('khach-san/danh-sach-phong', $langId, $hotelToken, ' - Rooms');
    }

    public function saveSelection(Request $request)
    {
        $validated = $request->validate([
            'kind' => 'required|string|in:hotel,roomtypes,rooms',
            'items' => 'required|array',
        ]);
        switch ($validated['kind']) {
            case 'hotel':
                // map fields -> Hotel model
                // Hotel::upsert($rows, ['external_id'], [...columns...]);
                foreach ($validated['items'] as $hotelData) {
                    $this->updateHotelData($hotelData);
                }
                break;
            case 'roomtypes':
                foreach ($validated['items'] as $hotelData) {
                    $this->updateRoomTypeHotel($hotelData);
                }
                break;
            case 'rooms':
                foreach ($validated['items'] as $hotelData) {
                    $this->updateRoomHotel($hotelData);
                }
                break;
        }
        return response()->json([
            'ok' => true,
            'saved' => count($validated['items']),
        ]);
    }
    /**
     * cập nhật dữ liệu khách sạn
     * @param array $hotelData
     * @return bool
     */
    private function updateHotelData(array $hotelData)
    {
        $token =HotelConfiguration::where('hotel_facility_id', hf('id'))->value('bearer_token');
        // Map TravelViet hotel data to local Hotel model field
        $mappedData = [
            'main_image' => $hotelData['hotel_image'] ?? null,
            'address' => $hotelData['lang_hotel_address'] ?? null,
            'hotel_name' => $hotelData['lang_hotel_name'] ?? null,
            'slug' => $hotelData['lang_hotel_slug'] ?? null,
            'latitude' => $hotelData['rel_latitude'] ?? null,
            'longitude' => $hotelData['rel_longitude'] ?? null,
            'province' => getProvinceName($hotelData['province_id']),
        ];
        $updated = HotelConfiguration::where('bearer_token', $token ?? null)
            ->update($mappedData);
        return $updated;
    }
    /**
     * cập nhật loại phòng
     * @param array $roomTypeData
     * @return void
     */
    private function updateRoomTypeHotel(array $roomTypeData)
    {
        // Lấy tên loại phòng
        $name = $roomTypeData['lang_cate_name'] ?? null;
        if (empty($name)) return;

        // Kiểm tra xem loại phòng đã tồn tại chưa
        $exists = RoomType::where('name', $name)->exists();

        if (!$exists) {
            // Sinh mã code duy nhất
            do {
                $code = getTrx(12);
            } while (RoomType::where('code', $code)->exists());

            // Tạo mới bản ghi
            $newRoomType = new RoomType();
            $newRoomType->code = $code;
            $newRoomType->status = 1;
            $newRoomType->name = $name;
            $newRoomType->subdomain = subdomain();
            $newRoomType->unit_code = unitCode();
            $newRoomType->save();
        }
    }

    /**
     * cập nhật phòng
     * @param array $roomData
     * @return void
     */
    private function updateRoomHotel(array $roomData)
    {
        try {
            //$roomData['list_uti'] danh sách tiện ích amenities
            //$roomData['list_array_image'] danh sách ảnh  room_images
            //lang_room_direction hướng phòng

            // ✅ Kiểm tra dữ liệu đầu vào
            if (empty($roomData['lang_room_name'])) {
                throw new \Exception("Thiếu tên phòng (lang_room_name)");
            }

            // ✅ Xử lý hướng phòng
            $roomDirectionName = $roomData['lang_room_direction'] ?? '';
            $room_directions = RoomDirection::where('name', $roomDirectionName)->first();

            if (!$room_directions) {
                do {
                    $code = getTrx(12);
                } while (RoomDirection::where('code', $code)->exists());

                $room_directions = new RoomDirection();
                $room_directions->code        = $code;
                $room_directions->name        = $roomDirectionName;
                $room_directions->subdomain   = subdomain();
                $room_directions->unit_code   = unitCode();
                $room_directions->save();
            }

            // ✅ Loại phòng
            $roomTypeName = $roomData['lang_cate_room'] ?? null;
            if (empty($roomTypeName)) {
                throw new \Exception("Thiếu loại phòng (lang_cate_room)");
            }

            $room_type = RoomType::where('name', $roomTypeName)->first();

            if (!$room_type) {
                // Sinh mã code duy nhất
                do {
                    $code = getTrx(12);
                } while (RoomType::where('code', $code)->exists());

                $room_type = new RoomType();
                $room_type->code = $code;
                $room_type->status = 1;
                $room_type->name = $roomTypeName;
                $room_type->subdomain = subdomain();
                $room_type->unit_code = unitCode();
                $room_type->save();
            }

            // ✅ Kiểm tra phòng đã tồn tại chưa
            $check_room = Room::where('room_number', $roomData['lang_room_name'])->first();

            if (!$check_room) {
                do {
                    $code = getTrx(12);
                } while (Room::where('code', $code)->exists());

                $room = new Room();
                $room->code          = $code;
                $room->room_type_id  = $room_type->id;
                $room->room_number   = $roomData['lang_room_name'] ?? null;
                $room->main_image    = $roomData['room_image'] ?? '';
                $room->total_adult   = $roomData['room_person'] ?? 0;
                $room->area          = $roomData['room_acreage'] ?? null;
                $room->description   = $roomData['lang_room_des'] ?? null;
                $room->beds          = $roomData['lang_room_bed'] ?? null;
                $room->direction_id  = $room_directions->id;
                $room->room_fix      = 0;
                $room->subdomain     = subdomain();
                $room->unit_code     = unitCode();
                $room->save();
                //  Nếu có danh sách ảnh thì thêm vào room_images
                if (!empty($roomData['list_array_image']) && is_array($roomData['list_array_image'])) {
                    foreach ($roomData['list_array_image'] as $imageUrl) {
                        if (!empty($imageUrl)) {
                            RoomImage::create([
                                'room_id'     => $room->id,
                                'image'   => $imageUrl,
                            ]);
                        }
                    }
                }
                // Thêm danh sách tiện ích (amenities)
                if (!empty($roomData['list_uti']) && is_array($roomData['list_uti'])) {
                    foreach ($roomData['list_uti'] as $uti) {
                        $title = $uti['uti_title'] ?? null;
                        if (empty($title)) continue;

                        // Kiểm tra tiện ích có tồn tại chưa
                        $amenity = Amenity::where('title', $title)->first();

                        if (!$amenity) {
                            do {
                                $amenityCode = getTrx(12);
                            } while (Amenity::where('code', $amenityCode)->exists());

                            $amenity = new Amenity();
                            $amenity->code = $amenityCode;
                            $amenity->title = $title;
                            $amenity->icon = $uti['uti_image'] ?? null;
                            $amenity->status = 1;
                            $amenity->subdomain = subdomain();
                            $amenity->unit_code = unitCode();
                            $amenity->save();
                        }

                        // Gắn vào bảng trung gian room_amenities
                        $exists = RoomTypeAmenity::where('room_id', $room->id)
                            ->where('amenities_id', $amenity->id)
                            ->exists();

                        if (!$exists) {
                            RoomTypeAmenity::create([
                                'room_id' => $room->id,
                                'amenities_id' => $amenity->id,
                                'subdomain' => subdomain(),
                                'unit_code' => unitCode(),
                            ]);
                        }
                    }
                }
                // ✅ Xử lý giá phòng (price_room)
                if (isset($roomData['price_room']) && $roomData['price_room'] !== null) {
                    try {
                        $price = $roomData['price_room'];
                        $currency = $roomData['lang_type_money'] ?? 'VND';
                        $currentDate = date('Y-m-d');

                        Log::info('Bắt đầu xử lý giá phòng', [
                            'price' => $price,
                            'currency' => $currency,
                            'currentDate' => $currentDate,
                            'room_type_id' => $room_type->id ?? null
                        ]);

                        // ✅ Tạo hoặc lấy SetupPricing cho từng phòng riêng biệt
                        $roomName = $roomData['lang_room_name'] ?? 'Unknown';
                        $priceName = 'giá bên travel - ' . $roomName;

                        $setupPricing = SetupPricing::where('price_requirement', json_encode([$currentDate]))
                            ->where('price_name', $priceName)
                            ->where('subdomain', subdomain())
                            ->where('unit_code', unitCode())
                            ->first();

                        if (!$setupPricing) {
                            Log::info('Tạo SetupPricing mới cho phòng: ' . $roomName);

                            // Tạo SetupPricing mới
                            do {
                                $priceCode = getTrx(8);
                            } while (SetupPricing::where('price_code', $priceCode)->exists());

                            Log::info('Generated price_code', ['price_code' => $priceCode]);

                            $setupPricingData = [
                                'price_code' => $priceCode,
                                'price_name' => $priceName,
                                'price_requirement' => json_encode([$currentDate]),
                                'description' => 'Giá từ TravelViet - ' . $roomName,
                                'subdomain' => subdomain(),
                                'unit_code' => unitCode(),
                            ];

                            Log::info('SetupPricing data to create', $setupPricingData);

                            $setupPricing = SetupPricing::create($setupPricingData);

                            Log::info('SetupPricing created successfully', ['id' => $setupPricing->id]);
                        } else {
                            Log::info('SetupPricing đã tồn tại cho phòng: ' . $roomName, ['id' => $setupPricing->id]);
                        }

                        // ✅ Tạo hoặc cập nhật RoomTypePrice (mỗi room_type_id sẽ có 1 bản ghi riêng)
                        $existingPrice = RoomTypePrice::where('room_type_id', $room_type->id)
                            ->where('setup_pricing_id', $setupPricing->id)
                            ->where('subdomain', subdomain())
                            ->where('unit_code', unitCode())
                            ->first();

                        if (!$existingPrice) {
                            Log::info('Tạo RoomTypePrice mới cho room_type_id: ' . $room_type->id);

                            $roomTypePriceData = [
                                'room_type_id' => $room_type->id,
                                'setup_pricing_id' => $setupPricing->id,
                                'unit_price' => $price,
                                'price_validity_period' => $currentDate,
                                'subdomain' => subdomain(),
                                'unit_code' => unitCode(),
                            ];

                            Log::info('RoomTypePrice data to create', $roomTypePriceData);

                            $roomTypePrice = RoomTypePrice::create($roomTypePriceData);

                            Log::info('RoomTypePrice created successfully', ['id' => $roomTypePrice->id]);
                        } else {
                            Log::info('Cập nhật RoomTypePrice hiện có cho room_type_id: ' . $room_type->id);

                            $existingPrice->update([
                                'unit_price' => $price,
                            ]);

                            Log::info('RoomTypePrice updated successfully');
                        }
                    } catch (\Exception $e) {
                        Log::error('Lỗi khi xử lý giá phòng: ' . $e->getMessage(), [
                            'room_name' => $roomData['lang_room_name'] ?? null,
                            'price' => $roomData['price_room'] ?? null,
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ghi log lỗi cụ thể để debug
            Log::error("Lỗi khi cập nhật phòng: " . $e->getMessage(), [
                'room_name' => $roomData['lang_room_name'] ?? null,
                'data' => $roomData,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
}
