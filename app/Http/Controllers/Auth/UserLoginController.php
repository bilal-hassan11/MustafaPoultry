<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends Controller 
{
    use AuthenticatesUsers;

    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest:user', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {   
        return view('auth.user.login');
    }

    public function login(\Illuminate\Http\Request $request)
    {   
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required'
        ]);
        
        if (Auth::guard('user')->attempt(['username' => $request->username, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(url('/web_admin'));
        }

        return redirect()
            ->back()
            ->withInput($request->only($request->only('username', 'remember')))
            ->withErrors(['username' => 'These credentials do not match our records.']);
    }

    public function logout()
    {
        Auth::guard('user')->logout();
        return redirect(route('user.login'));
    }
}
