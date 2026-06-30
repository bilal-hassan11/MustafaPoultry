<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function showLoginForm()
    {
        $data = [
            'title' => 'Login',
        ];
        return view('auth.login')->with($data);
    }

    /**
     * Called after successful authentication.
     * Generates a new session token and stores it in both the DB and the session.
     * This invalidates any existing session on another device/browser.
     */
    public function authenticated(Request $request, $user)
    {
        if ($user->user_type == 'monitor') {
            Auth::guard('admin')->logout();
            return redirect()->route('login')->withErrors(['active' => 'You are not authorized to access this page']);
        }

        // Generate a unique session token to track this login session
        $sessionToken = Str::random(60);

        // Store token in DB — overwrites any previous session token
        $user->session_token = $sessionToken;
        $user->save();

        // Store token in current session so we can verify it on every request
        $request->session()->put('admin_session_token', $sessionToken);

        return redirect()->intended($this->redirectPath());
    }

    public function logout(Request $request)
    {
        $user = auth('admin')->user();

        if ($user) {
            // Clear the session token from DB so no other session can use it
            $user->session_token = null;
            $user->save();
        }

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
