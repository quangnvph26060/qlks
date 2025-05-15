<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin;

class CheckAdminSubdomain
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost(); // vd: huhu.fasthotel.vn
        $baseDomain = 'fasthotel.vn';

        // Lấy subdomain
        if (str_ends_with($host, $baseDomain)) {
            $subdomain = str_replace('.' . $baseDomain, '', $host);

            if ($subdomain && $subdomain !== 'www') {
                // Check trong bảng admins
                $exists = Admin::where('username', $subdomain)->exists();

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
