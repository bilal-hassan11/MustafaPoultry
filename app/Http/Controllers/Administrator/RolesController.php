<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
        $this->middleware('is_admin');
    }

    public function index(Request $request)
    {
        $data = [
            'title' => 'All Roles',
            'roles' => $this->roleService->getAllRoles(),
        ];
        return view('admin.roles.all_roles')->with($data);
    }

    public function create()
    {
        $permissions = Permission::all();
        $groupedPermissions = $this->groupPermissionsByModule($permissions);
        
        $data = [
            'title' => 'Add Role',
            'groupedPermissions' => $groupedPermissions,
            'assigned_permissions' => [],
        ];
        return view('admin.roles.add_role')->with($data);
    }

    public function edit($roleId)
    {
        $role = $this->roleService->getRoleById($roleId);
        
        if ($role->slug === 'super-admin') {
            return back()->with('error', 'Super Admin role cannot be edited');
        }

        $permissions = Permission::all();
        $groupedPermissions = $this->groupPermissionsByModule($permissions);
        
        $data = [
            'title' => 'Edit Role',
            'role' => $role,
            'groupedPermissions' => $groupedPermissions,
            'assigned_permissions' => $role->permissions->pluck('name')->toArray(),
        ];
        return view('admin.roles.add_role')->with($data);
    }

    private function groupPermissionsByModule($permissions)
    {
        $modules = [
            'Dashboard',
            'Staffs',
            'Permissions',
            'Roles',
            'Account Types',
            'Accounts',
            'Payment Books',
            'Categories',
            'Items',
            'Cash Books',
            'Companies',
            'Medicine Invoices',
            'Feed Invoices',
            'Chick Invoices',
            'Murghi Invoices',
            'Other Invoices',
            'Stock',
            'Expenses',
            'Reports'
        ];
        $actions = ['Access', 'Create', 'Edit', 'Delete', 'Print'];

        $grouped = [];
        foreach ($modules as $module) {
            $grouped[$module] = [];
            foreach ($actions as $action) {
                $permissionName = $module . ' ' . $action;
                $permission = $permissions->firstWhere('name', $permissionName);
                if ($permission) {
                    $grouped[$module][] = $permission;
                }
            }
        }

        return $grouped;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:180', 'unique:roles,name'],
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        try {
            $this->roleService->createRole($request->all());
            return response()->json([
                'success' => 'Role created successfully',
                'redirect' => route('admin.roles.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => $e->getMessage()]], 500);
        }
    }

    public function update(Request $request, $roleId)
    {
        $role = $this->roleService->getRoleById($roleId);
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:180', 'unique:roles,name,' . $role->id . ',id'],
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        try {
            $this->roleService->updateRole($roleId, $request->all());
            return response()->json([
                'success' => 'Role updated successfully',
                'reload' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => $e->getMessage()]], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->roleService->deleteRole($request->role_id);
            return response()->json([
                'success' => 'Role deleted successfully',
                'remove_tr' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => $e->getMessage()]], 500);
        }
    }
}
