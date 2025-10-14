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

    public function searchHotel(Request $request)
    {
        $request->validate([
            'lang_id' => 'required',
            'hotel_name' => 'required|string',
        ]);

        $langId = $request->input('lang_id');
        $hotelName = $request->input('hotel_name');

        $token = env('TRAVELVIET_TOKEN');
        if (empty($token)) {
            return response()->json([
                'message' => 'Missing TRAVELVIET_TOKEN in environment.',
            ], 500);
        }

        $baseUrl = rtrim(env('TRAVELVIET_BASE_URL', 'http://127.0.0.1:8000'), '/');
        $url = $baseUrl . "/api/{$langId}/khach-san/tim-theo-ten/" . rawurlencode($hotelName);

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(60)
                ->get($url);

            if (!$response->ok()) {
                return response()->json([
                    'message' => 'Upstream API error',
                    'status' => $response->status(),
                    'body' => $response->json() ?? $response->body(),
                ], 502);
            }

            $data = $response->json();
            return response()->json([
                'ok' => true,
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to fetch from API',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function searchRoomTypes(Request $request)
    {
        $request->validate([
            'lang_id' => 'required',
            'hotel_name' => 'required|string',
        ]);

        $langId = $request->input('lang_id');
        $hotelName = $request->input('hotel_name');

        $token = env('TRAVELVIET_TOKEN');
        if (empty($token)) {
            return response()->json([
                'message' => 'Missing TRAVELVIET_TOKEN in environment.',
            ], 500);
        }

        $baseUrl = rtrim(env('TRAVELVIET_BASE_URL', 'http://127.0.0.1:8000'), '/');
        $url = $baseUrl . "/api/{$langId}/khach-san/danh-muc-phong-theo-ten/" . rawurlencode($hotelName);

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(60)
                ->get($url);

            if (!$response->ok()) {
                return response()->json([
                    'message' => 'Upstream API error',
                    'status' => $response->status(),
                    'body' => $response->json() ?? $response->body(),
                ], 502);
            }

            return response()->json([
                'ok' => true,
                'data' => $response->json(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to fetch from API',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function searchRooms(Request $request)
    {
        $request->validate([
            'lang_id' => 'required',
            'hotel_name' => 'required|string',
        ]);

        $langId = $request->input('lang_id');
        $hotelName = $request->input('hotel_name');

        $token = env('TRAVELVIET_TOKEN');
        if (empty($token)) {
            return response()->json([
                'message' => 'Missing TRAVELVIET_TOKEN in environment.',
            ], 500);
        }

        $baseUrl = rtrim(env('TRAVELVIET_BASE_URL', 'http://127.0.0.1:8000'), '/');
        $url = $baseUrl . "/api/{$langId}/khach-san/danh-sach-phong-theo-ten/" . rawurlencode($hotelName);

        try {
            $response = Http::withToken($token)
                ->acceptJson()
                ->timeout(60)
                ->get($url);

            if (!$response->ok()) {
                return response()->json([
                    'message' => 'Upstream API error',
                    'status' => $response->status(),
                    'body' => $response->json() ?? $response->body(),
                ], 502);
            }

            return response()->json([
                'ok' => true,
                'data' => $response->json(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Failed to fetch from API',
                'error' => $e->getMessage(),
            ], 500);
        }
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


