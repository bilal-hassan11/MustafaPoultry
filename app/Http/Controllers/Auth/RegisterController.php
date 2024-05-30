<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\CommonHelpers;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

// use Auth;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $data = array(
            'title' => 'Register',
        );
        return view('auth.register')->with($data);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function register(Request $request)
    {
        $rules = [
            'name' => ['required', 'max:191'],
            'email' => ['required', 'email', 'max:191', 'unique:admins'],
            'password' => ['required', 'min:6', 'confirmed'],
        ];
        
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }

        event(new Registered($user = $this->create($validator->validated(), $request)));

        // $this->guard()->login($user);

        return $this->registered($request, $user);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $validated, $request)
    {
        $verification_code = rand(0, 999999);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);

        if($request->hasFile('image')){
            $image = CommonHelpers::uploadSingleFile($request->file('image'), 'uploads/profile/');
            if(is_array($image)){
                return response()->json($image);
            }
            $user->image = $image;
        }
        
        $user->save();
        return $user;
    }

    protected function registered(Request $request, $user)
    {
        return response()->json([
            'success'   => 'Registered Successfully',
            'redirect'  => route('admin.home'),
        ]);
    }
}
