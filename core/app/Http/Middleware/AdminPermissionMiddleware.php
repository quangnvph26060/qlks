<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminPermissionMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        $codes = optional(auth('admin')->user()->role)->permissions->pluck('code')->toArray();
        $currentRoute = $request->route()->getName();
        $excludedRoutes = ['admin.revenue','admin.hotel.setup.amenities.search','admin.hotel.import.room.store','admin.hotel.room.type.statusAll'];
        /*
            'admin.revenue' => 'Danh thu trong màn thông kế'
            'admin.hotel.setup.amenities.search' => 'Tìm kiếm cài đặt tiện nghi'
            'admin.hotel.import.room.store' => import room
            'admin.hotel.room.type.statusAll' => update nhiều trạng thái phòng 
        
        */
        if (!in_array($currentRoute, $excludedRoutes) && !in_array($currentRoute, $codes)) {
             Log::warning('Access denied for route: ' . $currentRoute);
            if (url()->previous() == url()->current()) {
                abort(403, 'Bạn không có quyền truy cập vào trang này .');
            }
           abort(403, 'Bạn không có quyền truy cập vào trang này.');
        }


        return $next($request);
        // if (!Role::hasPermission()) {
        //     return redirect()->route('admin.profile');
        // }

        // return $next($request);
    }
}
