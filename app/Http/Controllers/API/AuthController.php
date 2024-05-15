<?php

namespace App\Http\Controllers\API;

use App\Helpers\CommonHelpers;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\UserLogin;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class AuthController extends ApiController
{

    public function __construct()
    {

        $this->middleware('auth:api', ['except' => ['login','register', 'forgot_password']]);
    }

    public function login(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'username' => ['string', 'required'],
            'password' => ['required'],
        ]);

        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }

        $credentials = $request->only('username', 'password');

        $token = Auth::guard('api')->attempt($credentials);
        // dd($token);
        if (!$token) {
            return api_response(false, null, 'Username or password is invalid');
        }

        $user = auth('api')->user();

        if(!$user->is_monitor){
            Auth::guard('api')->logout();
            return api_response(false, null, 'You are not allowed to login on the mobile application');
        }

        if($user->is_active == false){
            return api_response(false, null, 'You account has been deactivated by administrator. Please contact administrator for further details.');
        }

        $user->device_token = $request->header('devicetoken');
        $user->save();

        // Login Activity Logs
        UserLogin::create([
            'device_token' => $request->header('devicetoken'),
            'platform' => $request->header('platform'),
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'user_agent' => $request->header('user-agent'),
        ]);

        // Activity Logs
        CommonHelpers::activity_logs($user->full_name.' Logged in on app', platform: $request->header('platform'), user_id: $user->id);
        $data = array(
            'token' => $token,
            'user' => $user,
        );
        return api_response(true, $data, 'Successufully logged in');

    }

    public function register(Request $request){
        $rules = [
            'full_name' => ['required', 'string', 'max:160'],
            'username' => ['string', 'required', 'max:255', 'unique:admins'],
            'email' => ['string', 'email', 'max:255', 'unique:admins'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'contact_no' => ['required', 'string', 'regex:/^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/'],
            'cnic' => ['required', 'string', 'max:15', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]$/'],
        ];

        $error_msgs = [
            'contact_no.regex' => 'Contact number must be in the format: +92-333-1234567',
            'cnic.regex' => 'CNIC must be in the format: 41304-1234567-1',
        ];

        $validation = Validator::make($request->all(), $rules, $error_msgs);

        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }

        $user = new Admin();
        if($request->hasFile('profile_image')){
            $profile_img = CommonHelpers::uploadSingleFile($request->file('profile_image'), 'uploads/profile_images/', 'png,gif,jpeg,jpg', '1500');
            if (is_array($profile_img)) {
                return api_response(false, null, $profile_img['error']);
            }

            $user->image = $profile_img;
        }

        $user->added_by_id = 0;
        $user->is_active = 1;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->user_permissions = [];
        $user->name = $request->full_name;
        $user->cnic = $request->cnic;
        $user->contact_no = $request->contact_no;
        $user->user_type = 'normal';
        $user->device_token = $request->header('devicetoken');
        $user->save();

        $token = auth('api')->login($user);

        UserLogin::create([
            'device_token' => $request->header('devicetoken'),
            'platform' => $request->header('platform'),
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'user_agent' => $request->header('user-agent'),
        ]);

        // Activity Logs
        CommonHelpers::activity_logs('New user registered from app', platform: $request->header('platform'), user_id: $user->id);
        $data = array(
            'token' => $token,
            'user' => $user
        );
        return api_response(true, $data, null);
    }

    public function logout(Request $request){
        $user = $this->current_user();
        UserLogin::whereUserId($user->id)->update(['device_token' => null]);
        Admin::whereId($user->id)->update(['device_token' => null]);
        auth('api')->logout();

        // Activity Logs
        CommonHelpers::activity_logs($user->full_name .' logged out from app', platform: $request->header('platform'), user_id: $user->id);
        return api_response(true, null, 'You are successfully logged out');
    }

    public function refresh(Request $request){
        $data = [
            'token' => Auth::guard('api')->refresh(),
        ];

        // Activity Logs
        $user = $this->current_user();
        CommonHelpers::activity_logs('Token refreshed from app', platform: $request->header('platform'), user_id: $user->id);
        return api_response(true, $data);
    }

    public function update_profile(Request $request){
        $rules = [
            'full_name' => ['required', 'string', 'max:160'],
            'contact_no' => ['required', 'string', 'regex:/^((\+92)|(0092))-{0,1}\d{3}-{0,1}\d{7}$|^\d{11}$|^\d{4}-\d{7}$/'],
            'cnic' => ['required', 'string', 'max:15', 'regex:/^[0-9]{5}-[0-9]{7}-[0-9]$/'],
        ];

        $error_msgs = [
            'contact_no.regex' => 'Contact number must be in the format: +92-333-1234567',
            'cnic.regex' => 'CNIC must be in the format: 41304-1234567-1',
        ];

        $validation = Validator::make($request->all(), $rules, $error_msgs);

        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }

        $user = auth('api')->user();

        if($request->hasFile('profile_image')){
            $profile_img =  CommonHelpers::uploadSingleFile($request->file('profile_image'), 'uploads/profile_images/', 'png,gif,jpeg,jpg', '1500');
            if (is_array($profile_img)) {
                return api_response(false, null, $profile_img['error']);
            }
            $user->image = $profile_img;
        }

        $user->name = $request->full_name;
        $user->cnic = $request->cnic;
        $user->contact_no = $request->contact_no;
        $user->save();

        $data = array(
            'user' => $user
        );

        // Activity Logs
        CommonHelpers::activity_logs('User Profile updated from app', platform: $request->header('platform'), user_id: $user->id);
        return api_response(true, $data, 'You profile updated successfully');
    }

    public function change_password(Request $request){
        $validation = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|same:password|min:8',
            'password_confirmation' => 'required|same:password|min:8',
        ]);

        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }

        $user = $this->current_user();

        if (!(Hash::check($request->old_password, $user->password))) {
            return api_response(false, null, 'Your current password does not matches with the password you provided. Please try again.');
        }

        if(strcmp($request->old_password, $request->password) == 0){
            return api_response(false, null, 'New Password cannot be same as your current password. Please choose a different password.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Activity Logs$user->save();
        CommonHelpers::activity_logs('Password changed', platform: $request->header('platform'), user_id: $user->id);
        return api_response(true, null, 'Your password have been successfully changed');
    }

    public function forgot_password(Request $request){

        $validation = Validator::make($request->all(), [
            'email' => "required|email",
        ]);

        if ($validation->fails()) {
            return api_response(false, null, $validation->errors()->first());
        }
        try {
            $response = Password::sendResetLink($request->only('email'));
            switch ($response) {
                case Password::RESET_LINK_SENT:
                    return api_response(true, null, 'Rest Password instructions has been sent to your email kindly check your inbox and follow the instructions mentioned in the email');
                case Password::INVALID_USER:
                    return api_response(false, null, 'No user found with the given email in our database');
                default:
                    return api_response(false, null, 'This request can\'t be handled at this moment');
            }
        } catch (\Exception $ex) {
            return api_response(false, null, $ex->getMessage());
        }
    }

}
