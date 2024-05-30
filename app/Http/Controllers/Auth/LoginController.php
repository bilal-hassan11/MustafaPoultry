<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\CommonHelpers;
use App\Http\Controllers\Controller;
// use App\Models\UserLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

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
    public function username()
    {
        return 'username';
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        $data = array(
            'title' => 'Login',
        );

        return view('auth.login')->with($data);
    }

    public function authenticated($request, $user)
    {
        if($user->user_type == 'monitor'){
            Auth::guard('admin')->logout();
            return redirect()->route('login')->withErrors(['active' => 'You are not authorized to access this page']);
        }else{
        }
        return redirect()->intended($this->redirectPath());
    }

    public function logout()
    {   
        $user = auth('admin')->user();
        Auth::guard('admin')->logout();
        return redirect(route('admin.home'));
    }
}
