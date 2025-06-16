<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class RedirectIfAdmin
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
        if (Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();
                if($user->role_id == 1){
                    return to_route('admin.display');
                }else{
                    return to_route('admin.receptionist.booking.receptionist'); // lễ tân
                }
              
        }
        return $next($request);
    }
}
