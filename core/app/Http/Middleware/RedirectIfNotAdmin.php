<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        if (!Auth::guard($guard)->check()) {
            return to_route('admin.login');
        }

        $admin = Auth::guard('admin')->user();
        if ($admin->id != 0 && !$admin->status) {
            Auth::guard('admin')->logout();
            session()->invalidate();
            session()->regenerateToken();

            $notify[] = ['error', 'Your profile is currently disabled'];
            return to_route('admin.login')->withNotify($notify);
        }

        return $next($request);
    }
}
