<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;

class TravelVietController extends Controller
{
    public function index()
    {
        return view('admin.travelviet.index');
    }

    /**
     * Helper method to make API calls to TravelViet
     */
    private function makeTravelVietApiCall($endpoint, $langId, $hotelName, $operation = '')
    {
        $token = env('TRAVELVIET_TOKEN');
        if (empty($token)) {
            \Log::error('TravelViet API: Missing TRAVELVIET_TOKEN in environment');
            return response()->json([
                'message' => 'Cấu hình API chưa đầy đủ. Vui lòng liên hệ quản trị viên để cấu hình TRAVELVIET_TOKEN.',
                'error_code' => 'MISSING_TOKEN'
            ], 500);
        }

        $baseUrl = rtrim(env('TRAVELVIET_BASE_URL', 'http://127.0.0.1:8000'), '/');
        $url = $baseUrl . "/api/{$langId}/{$endpoint}/" . rawurlencode($hotelName);

        \Log::info("TravelViet API Request{$operation}", [
            'url' => $url,
            'hotel_name' => $hotelName,
            'lang_id' => $langId
        ]);

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(30)
                ->get($url);

            if (!$response->ok()) {
                \Log::error("TravelViet API Error{$operation}", [
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
            \Log::info("TravelViet API Success{$operation}", [
                'data_count' => is_array($data) ? count($data) : 'unknown',
                'data' => $data,
                'url' => $url,
                'hotel_name' => $hotelName,
                'lang_id' => $langId
            ]);
            
            return response()->json([
                'ok' => true,
                'data' => $data,
            ]);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            \Log::error("TravelViet API Connection Error{$operation}", [
                'error' => $e->getMessage(),
                'url' => $url
            ]);
            return response()->json([
                'message' => 'Không thể kết nối đến server API TravelViet. Vui lòng kiểm tra kết nối mạng hoặc liên hệ quản trị viên.',
                'error_code' => 'CONNECTION_ERROR'
            ], 500);
        } catch (\Throwable $e) {
            \Log::error("TravelViet API Unexpected Error{$operation}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Có lỗi xảy ra khi gọi API TravelViet: ' . $e->getMessage(),
                'error_code' => 'UNEXPECTED_ERROR'
            ], 500);
        }
    }

    public function searchHotel(Request $request)
    {
        $request->validate([
            'lang_id' => 'required',
            'hotel_name' => 'required|string',
        ]);

        $langId = $request->input('lang_id');
        $hotelName = $request->input('hotel_name');

        return $this->makeTravelVietApiCall('khach-san/tim-theo-ten', $langId, $hotelName, ' - Hotel Search');
    }

    public function searchRoomTypes(Request $request)
    {
        $request->validate([
            'lang_id' => 'required',
            'hotel_name' => 'required|string',
        ]);
        $langId = $request->input('lang_id');
        $hotelName = $request->input('hotel_name');

        return $this->makeTravelVietApiCall('khach-san/danh-muc-phong-theo-ten', $langId, $hotelName, ' - Room Types');
    }

    public function searchRooms(Request $request)
    {
        $request->validate([
            'lang_id' => 'required',
            'hotel_name' => 'required|string',
        ]);

        $langId = $request->input('lang_id');
        $hotelName = $request->input('hotel_name');

        return $this->makeTravelVietApiCall('khach-san/danh-sach-phong-theo-ten', $langId, $hotelName, ' - Rooms');
    }

    public function saveSelection(Request $request)
    {
        $validated = $request->validate([
            'kind' => 'required|string|in:hotel,roomtypes,rooms',
            'items' => 'required|array',
        ]);
        // TODO: Persist $validated['items'] into database as needed
        switch ($validated['kind']) {
            case 'hotel':
              // map fields -> Hotel model
              // Hotel::upsert($rows, ['external_id'], [...columns...]);
              break;
            case 'roomtypes':
              // map fields -> RoomType model
              break;
            case 'rooms':
              // map fields -> Room model
              break;
          }
        return response()->json([
            'ok' => true,
            'saved' => count($validated['items']),
        ]);
    }
}


