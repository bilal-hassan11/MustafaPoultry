<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Services\Slug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
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

    public function index(Request $request)
    {
        if (!auth('admin')->user()->hasPermissionTo('Permissions Access')) {
            abort(403, 'Unauthorized action.');
        }
        
        $data = array(
            'title' => 'All Permissions',
            'permissions' => Permission::orderby('id', 'desc')->get(),
        );
        return view('admin.permissions.all_permissions')->with($data);
    }

    public function save(Request $request, Slug $slug)
    {   
        if (!$request->permission_id && !auth('admin')->user()->hasPermissionTo('Permissions Create')) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($request->permission_id && !auth('admin')->user()->hasPermissionTo('Permissions Edit')) {
            abort(403, 'Unauthorized action.');
        }
        
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
        if (!auth('admin')->user()->hasPermissionTo('Permissions Delete')) {
            abort(403, 'Unauthorized action.');
        }
        
        $permission = Permission::hashidOrFail($request->permission_id);
        $permission->delete();
        return response()->json([
            'success' => 'Permission deleted successfully',
            'remove_tr' => true
        ]);
    }
}
