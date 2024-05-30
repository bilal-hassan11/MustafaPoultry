<?php

namespace App\Http\Controllers\Administrator;

use App\Helpers\CommonHelpers;
use App\Models\Admin as Staff;
use App\Http\Controllers\Controller;
use App\Services\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{

    public function __construct()
    {
        $this->middleware('is_admin',  ['except' => ['update_profile', 'save_profile', 'change_password']]);
    }

    public function index(Request $request)
    {   
        $data = array(
            'title' => 'All Staff Users',
            'staffs' => Staff::where('id', '!=', auth('admin')->user()->id)
                            ->when(isset($request->name), function($query) use ($request){
                                $query->where('first_name', 'like', '%'.$request->name.'%')->orWhere('last_name', 'like', '%'.$request->name.'%');
                            })
                            ->when(isset($request->status), function($query) use ($request){
                                $query->where('is_active', $request->status);
                            })
                            ->latest()->get()
        );
        return view('admin.staffs.all_staffs')->with($data);
    }

    public function add()
    {   
        $data = array(
            'title'       => 'Add Staff User',
            'permissions' => \App\Models\Permission::get(),
        );
        return view('admin.staffs.add_staff')->with($data);
    }

    public function edit($staff_id)
    {
        $staff = Staff::hashidOrFail($staff_id);
        $data = array(
            'title' => 'Edit Staff User',
            'user' => $staff,
            'permissions' => \App\Models\Permission::all(),
            'user_permissions' => array_column($staff->user_permissions, 'name'),

        );

        return view('admin.staffs.add_staff')->with($data);
    }

    public function save(Request $request, Slug $slug)
    {
        $rules = [
            // 'first_name' => ['required', 'string', 'max:80'],
            'name' => ['string', 'max:80'],
            'user_type' => ['required', 'string', 'in:admin,normal'],
            // 'company_id' => ['required'],
            'email' => ['required', 'string', 'email', 'max:190'],
        ];

        if (!$request->user_id) {
            $rules['username'] = ['required', 'string', 'unique:admins', 'max:100'];
            $rules['password'] = ['required', 'string', 'min:8'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        if ($request->user_id) {
            $staff = Staff::hashidFind($request->user_id);
            $msg = [
                'success' => 'Staff User has been updated',
                'reload' => true,
            ];
        } else {
            $staff = new Staff();
            // $staff->added_by_id = auth()->user()->id;
            $staff->is_active = 1;
            $staff->username = $request->username;
            $staff->password = Hash::make($request->password);

            $msg = [
                'success' => 'Staff User has been added',
                'redirect' => route('admin.staffs.all'),
            ];
        }

        if ($request->hasFile('profile_img')) {
            $profile_img = CommonHelpers::uploadSingleFile($request->file('profile_img'), 'uploads/profile_images/');
            if (is_array($profile_img)) {
                return response()->json($profile_img);
            }
            if (file_exists($staff->image)) {
                @unlink($staff->image);
            }
            $staff->image = $profile_img;
        }

        $permissions = [];
        if(isset($request->permissions) && safeCount($request->permissions) > 0){
            foreach($request->permissions as $permission){
                $_permission = \App\Models\Permission::firstOrNew(['name' => $permission]);
                if($_permission->id == null){
                    $_permission->slug = $slug->createSlug('permissions', $permission);
                    $_permission->save();
                }

                $permissions[] = ['name' => $_permission->slug];
            }
        }

        $staff->email = $request->email;
        $staff->username = $request->name;
        $staff->user_type = $request->user_type;
        $staff->user_permissions = $permissions;
        // $staff->company_id = $request->company_id;
        $staff->save();

        return response()->json($msg);
    }


    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $msg = [
            'success' => 'Staff User password has been updated',
            'reload' => true,
        ];

        $staff = Staff::hashidFind($request->user_id);
        $staff->password = Hash::make($request->password);
        $staff->save();

        return response()->json($msg);
    }

    public function delete(Request $request)
    {
        $staff = Staff::hashidFind($request->user_id);
        $staff->delete();
        return response()->json([
            'success' => 'Staff User deleted successfully',
            'remove_tr' => true
        ]);
    }

    public function updateStatus($staff_id)
    {
        $staff = Staff::hashidFind($staff_id);
        $staff->is_active = !$staff->is_active;
        $staff->save();
        return response()->json([
            'success' => 'Staff User Status Updated Successfully',
            'reload' => true
        ]);
    }

    public function update_profile(Request $request)
    {
        $data = array(
            'title' => 'Edit Profile',
            'user' => Staff::find(auth('admin')->user()->id)
        );
        return view('admin.users.edit_profile')->with($data);
    }

    public function save_profile(Request $request)
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name' => ['required', 'string', 'max:80'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $staff = Staff::find(auth('admin')->user()->id);

        if ($request->hasFile('profile_img')) {
            $profile_img = CommonHelpers::uploadSingleFile($request->file('profile_img'), 'upload/profile_images/');
            if (is_array($profile_img)) {
                return response()->json($profile_img);
            }
            if (file_exists($staff->image)) {
                @unlink($staff->image);
            }
            $staff->image = $profile_img;
        }

        $staff->first_name = $request->first_name;
        $staff->last_name = $request->last_name;
        $staff->save();

        $msg = [
            'success' => 'Your profile has been updated',
            'reload' => true,
        ];

        return response()->json($msg);
    }

    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        $msg = [
            'success' => 'Your password has been changed',
            'reload' => true,
        ];

        $staff = Staff::find(auth()->user()->id);

        if (!(Hash::check($request->old_password, $staff->password))) {
            return response()->json([
                'error' => 'Your current password does not matches with the password you provided. Please try again.',
            ]);
        }

        if (strcmp($request->old_password, $request->password) == 0) {
            return response()->json([
                'error' => 'New Password cannot be same as your current password. Please choose a different password.',
            ]);
        }

        $staff->password = Hash::make($request->password);
        $staff->save();

        return response()->json($msg);
    }
}
