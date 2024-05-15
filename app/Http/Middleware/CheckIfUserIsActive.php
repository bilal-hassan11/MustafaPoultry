<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CheckIfUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $curr_guard = "user";
        $login_page = 'user.login';
        if (Route::is('admin.*')) {
            $login_page = 'login';
            $curr_guard = "admin";
        }
        if (auth($curr_guard)->check()) {
            if(!@auth($curr_guard)->user()->is_active){
                Auth::guard($curr_guard)->logout();
                return redirect(route($login_page))->withErrors([
                    'active' => 'You account has been deactivated by administrator. Please contact administrator for further details.'
                ]);
            }
        }
        return $next($request);
    }
}
