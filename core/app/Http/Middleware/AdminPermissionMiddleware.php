<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;

class AdminPermissionMiddleware
{

    public function handle(Request $request, Closure $next)
    {

        if (!Role::hasPermission()) {
            return redirect()->route('admin.profile');
        }

        return $next($request);
    }
}
