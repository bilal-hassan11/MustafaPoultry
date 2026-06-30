<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSingleSession
{
    /**
     * Handle an incoming request.
     *
     * If the session token stored in this browser session no longer matches
     * the token in the database, it means the user has logged in from
     * another device. We force-logout this old session and redirect to login
     * with a descriptive message.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth('admin')->check()) {
            $user = auth('admin')->user();
            $sessionToken = $request->session()->get('admin_session_token');

            // If no token in session, or it doesn't match DB → force logout
            if (!$sessionToken || $sessionToken !== $user->session_token) {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors([
                        'session' => 'Your session was ended because you logged in from another device or browser. Please log in again.',
                    ]);
            }
        }

        return $next($request);
    }
}
