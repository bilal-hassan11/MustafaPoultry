<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Services\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('is_admin');
        
    // }

    public function index(Request $request)
    {
        $data = array(
            'title' => 'All Permissions',
            'permissions' => Permission::orderby('id', 'desc')->get(),
        );
        return view('admin.permissions.all_permissions')->with($data);
        // return view('admin.ranges.add_range')->with($data);

    }

    public function save(Request $request, Slug $slug)
    {   
        $rules = [
            'name' => ['required', 'string', 'max:80'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }


        if ($request->permission_id) {
            $permission = Permission::hashidFind($request->permission_id);
            $permission->slug = $slug->createSlug('permissions', $request->name, $permission->id);
            $msg = [
                'success' => 'Permission has been updated',
                'reload' => true,
            ];
        } else {
            $permission = new Permission();
            $permission->slug = $slug->createSlug('permissions', $request->name);
            $msg = [
                'success' => 'Permission has been added',
                'redirect' => route('admin.permissions.index'),
            ];
        }

        $permission->name = $request->name;
        $permission->save();

        return response()->json($msg);
    }

    public function delete(Request $request)
    {
        $permission = Permission::hashidOrFail($request->permission_id);
        $permission->delete();
        return response()->json([
            'success' => 'Permission deleted successfully',
            'remove_tr' => true
        ]);
    }
}
