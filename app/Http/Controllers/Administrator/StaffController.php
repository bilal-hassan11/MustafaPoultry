<?php

namespace App\Http\Controllers\Administrator;

use App\Helpers\CommonHelpers;
use App\Models\Admin as Staff;
use App\Models\Role;
use App\Http\Controllers\Controller;
use App\Services\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth('admin')->check()) {
                return redirect()->route('login');
            }
            return $next($request);
        });
    }

    // ─── List ────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        if (!auth('admin')->user()->hasPermissionTo('Staffs Access')) {
            abort(403, 'Unauthorized action.');
        }

        $data = [
            'title'  => 'All Staff Users',
            'staffs' => Staff::where('id', '!=', auth('admin')->user()->id)
                ->with('roles')
                ->when($request->filled('name'), function ($q) use ($request) {
                    $q->where(function ($q2) use ($request) {
                        $q2->where('first_name', 'like', '%' . $request->name . '%')
                           ->orWhere('last_name',  'like', '%' . $request->name . '%');
                    });
                })
                ->when($request->filled('status'), function ($q) use ($request) {
                    $q->where('is_active', $request->status);
                })
                ->latest()
                ->get(),
        ];

        return view('admin.staffs.all_staffs')->with($data);
    }

    // ─── Add form ────────────────────────────────────────────────────────────
    public function add()
    {
        if (!auth('admin')->user()->hasPermissionTo('Staffs Create')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.staffs.add_staff', [
            'title' => 'Add Staff User',
            'roles' => Role::orderBy('name')->get(),
        ]);
    }

    // ─── Edit form ───────────────────────────────────────────────────────────
    public function edit($staff_id)
    {
        if (!auth('admin')->user()->hasPermissionTo('Staffs Edit')) {
            abort(403, 'Unauthorized action.');
        }

        $staff = Staff::with('roles')->hashidFindOrFail($staff_id);

        return view('admin.staffs.add_staff', [
            'title'      => 'Edit Staff User',
            'user'       => $staff,
            'roles'      => Role::orderBy('name')->get(),
            'user_roles' => $staff->roles->pluck('id')->toArray(),
        ]);
    }

    // ─── Save (create & update) ──────────────────────────────────────────────
    public function save(Request $request)
    {
        $isUpdate = (bool) $request->filled('user_id');

        // Permission check
        if (!$isUpdate && !auth('admin')->user()->hasPermissionTo('Staffs Create')) {
            abort(403, 'Unauthorized action.');
        }
        if ($isUpdate && !auth('admin')->user()->hasPermissionTo('Staffs Edit')) {
            abort(403, 'Unauthorized action.');
        }

        // Resolve the current record's DB id for unique-ignore on update
        $ignoreId = null;
        if ($isUpdate) {
            $existingStaff = Staff::hashidFind($request->user_id);
            if (!$existingStaff) {
                return response()->json(['errors' => ['user_id' => ['Staff user not found.']]], 404);
            }
            $ignoreId = $existingStaff->id;
        }

        // Validation rules
        $rules = [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name'  => ['required', 'string', 'max:80'],
            'email'      => [
                'required', 'string', 'email', 'max:190',
                'unique:admins,email' . ($isUpdate ? ',' . $ignoreId : ''),
            ],
            'role'       => ['nullable', 'exists:roles,id'],
        ];

        if (!$isUpdate) {
            $rules['username'] = ['required', 'string', 'max:100', 'unique:admins,username', 'regex:/^\S+$/'];
            $rules['password'] = ['required', 'string', 'min:8'];
        }

        $validator = Validator::make($request->all(), $rules, [
            'email.unique'    => 'This email address is already registered.',
            'username.unique' => 'This username is already taken.',
            'username.regex'  => 'Username must not contain spaces.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            if ($isUpdate) {
                $staff = $existingStaff;
                $msg   = ['success' => 'Staff user has been updated.', 'reload' => true];
            } else {
                $staff             = new Staff();
                $staff->is_active  = 1;
                $staff->username   = $request->username;
                $staff->password   = Hash::make($request->password);
                $staff->user_type  = 'normal';
                $msg               = [
                    'success'  => 'Staff user has been added.',
                    'redirect' => route('admin.staffs.all'),
                ];
            }

            // Profile image upload
            if ($request->hasFile('profile_img')) {
                $profile_img = CommonHelpers::uploadSingleFile(
                    $request->file('profile_img'),
                    'uploads/profile_images/'
                );
                if (is_array($profile_img)) {
                    return response()->json($profile_img, 422);
                }
                if ($staff->image && file_exists($staff->image)) {
                    @unlink($staff->image);
                }
                $staff->image = $profile_img;
            }

            $staff->email            = $request->email;
            $staff->first_name       = $request->first_name;
            $staff->last_name        = $request->last_name;
            $staff->user_permissions = null; // roles are used instead
            $staff->save();

            // Sync single role
            $staff->roles()->sync($request->filled('role') ? [$request->role] : []);

            return response()->json($msg);

        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => 'An unexpected error occurred: ' . $e->getMessage()]], 500);
        }
    }

    // ─── Update password ─────────────────────────────────────────────────────
    public function update_password(Request $request)
    {
        if (!auth('admin')->user()->hasPermissionTo('Staffs Edit')) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'user_id'               => ['required'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $staff = Staff::hashidFindOrFail($request->user_id);
            $staff->password = Hash::make($request->password);
            $staff->save();

            return response()->json(['success' => 'Staff user password has been updated.', 'reload' => true]);

        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => 'Staff user not found.']], 404);
        }
    }

    // ─── Delete ──────────────────────────────────────────────────────────────
    public function delete(Request $request)
    {
        if (!auth('admin')->user()->hasPermissionTo('Staffs Delete')) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $staff = Staff::hashidFindOrFail($request->user_id);

            // Prevent deleting yourself
            if ($staff->id === auth('admin')->user()->id) {
                return response()->json(['errors' => ['general' => 'You cannot delete your own account.']], 422);
            }

            $staff->roles()->detach();
            $staff->delete();

            return response()->json(['success' => 'Staff user deleted successfully.', 'remove_tr' => true]);

        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => 'Staff user not found.']], 404);
        }
    }

    // ─── Toggle active status ─────────────────────────────────────────────────
    public function updateStatus($staff_id)
    {
        if (!auth('admin')->user()->hasPermissionTo('Staffs Edit')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $staff = Staff::hashidFindOrFail($staff_id);

            if ($staff->id === auth('admin')->user()->id) {
                return response()->json(['errors' => ['general' => 'You cannot change your own status.']], 422);
            }

            $staff->is_active = !$staff->is_active;
            $staff->save();

            return response()->json(['success' => 'Staff user status updated successfully.', 'reload' => true]);

        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => 'Staff user not found.']], 404);
        }
    }

    // ─── Profile ─────────────────────────────────────────────────────────────
    public function update_profile(Request $request)
    {
        return view('admin.users.edit_profile', [
            'title' => 'Edit Profile',
            'user'  => Staff::find(auth('admin')->user()->id),
        ]);
    }

    public function save_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:80'],
            'last_name'  => ['required', 'string', 'max:80'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $staff = Staff::findOrFail(auth('admin')->user()->id);

            if ($request->hasFile('profile_img')) {
                $profile_img = CommonHelpers::uploadSingleFile(
                    $request->file('profile_img'),
                    'uploads/profile_images/'
                );
                if (is_array($profile_img)) {
                    return response()->json($profile_img, 422);
                }
                if ($staff->image && file_exists($staff->image)) {
                    @unlink($staff->image);
                }
                $staff->image = $profile_img;
            }

            $staff->first_name = $request->first_name;
            $staff->last_name  = $request->last_name;
            $staff->save();

            return response()->json(['success' => 'Your profile has been updated.', 'reload' => true]);

        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => 'An error occurred while updating your profile.']], 500);
        }
    }

    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => ['required', 'string'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $staff = Staff::findOrFail(auth('admin')->user()->id);

            if (!Hash::check($request->old_password, $staff->password)) {
                return response()->json(['errors' => ['old_password' => ['Current password is incorrect.']]], 422);
            }

            if ($request->old_password === $request->password) {
                return response()->json(['errors' => ['password' => ['New password must be different from the current password.']]], 422);
            }

            $staff->password = Hash::make($request->password);
            $staff->save();

            return response()->json(['success' => 'Your password has been changed.', 'reload' => true]);

        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => 'An error occurred.']], 500);
        }
    }
}
