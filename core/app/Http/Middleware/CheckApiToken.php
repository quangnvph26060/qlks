<?php

namespace App\Http\Middleware;

use App\Models\Ota;
use App\Models\OtaSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next): Response
    // {
    //     $token = $request->bearerToken();
    //     $validToken =   env('KEY_PUBLIC');

    //     if ($token !== $validToken) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Missing token'], 401);
        }

        // Kiểm tra token trong bảng otas
        $ota = Ota::where('api_token', $token)->first();

        if (!$ota) {
            return response()->json(['error' => 'Invalid token'], 401);
        }

        // Kiểm tra ota_id trong bảng ota_settings và phải có status = 1
        $otaSetting = OtaSetting::where('ota_id', $ota->id)
            ->where('status', 1)
            ->first();

        if (!$otaSetting) {
            return response()->json(['error' => 'OTA not authorized or inactive'], 401);
        }

        // Gửi ota_id kèm theo request nếu cần xử lý tiếp
        $request->merge(['ota_id' => $ota->id]);

        return $next($request);
    }
}
