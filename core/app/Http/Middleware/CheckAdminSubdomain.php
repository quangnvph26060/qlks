<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Cache;

class CheckAdminSubdomain
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost(); // ví dụ: huhu.fasthotel.vn
        $baseDomain = 'fasthotel.vn';

        // Kiểm tra nếu domain là dạng *.fasthotel.vn
        if (str_ends_with($host, $baseDomain)) {
            $subdomain = str_replace('.' . $baseDomain, '', $host);

            // Bỏ qua nếu là app.fasthotel.vn
            if ($subdomain === 'app') {
                return $next($request);
            }

            // Kiểm tra subdomain khác rỗng và không phải www
            if ($subdomain && $subdomain !== 'www') {
                // Dùng cache để giảm truy vấn DB
                $cacheKey = 'subdomain_check_' . $subdomain;
                $authLifetime = config('session.lifetime'); // đơn vị: phút
                $exists = Cache::remember($cacheKey, now()->addMinutes($authLifetime), function () use ($subdomain) {
                    return Admin::where('username', $subdomain)->exists();
                });

                if (!$exists) {
                    abort(403, 'Bạn không có quyền truy cập subdomain này.');
                }
            } else {
                abort(403, 'Subdomain không hợp lệ.');
            }
        } else {
            abort(403, 'Domain không hợp lệ.');
        }
        return $next($request);
    }
}
