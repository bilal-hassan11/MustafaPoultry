<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
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
        if (!auth('admin')->user()->hasPermissionTo('Roles Access')) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.roles.all_roles', [
            'title' => 'All Roles',
            'roles' => $this->roleService->getAllRoles(),
        ]);
    }

    // ─── Create form ─────────────────────────────────────────────────────────
    public function create()
    {
        if (!auth('admin')->user()->hasPermissionTo('Roles Create')) {
            abort(403, 'Unauthorized action.');
        }

        $permissions       = Permission::orderBy('name')->get();
        $groupedPermissions = $this->groupPermissionsByModule($permissions);

        return view('admin.roles.add_role', [
            'title'              => 'Add Role',
            'groupedPermissions' => $groupedPermissions,
            'assigned_permissions' => [],
        ]);
    }

    // ─── Edit form ───────────────────────────────────────────────────────────
    public function edit($roleId)
    {
        if (!auth('admin')->user()->hasPermissionTo('Roles Edit')) {
            abort(403, 'Unauthorized action.');
        }

        $role = $this->roleService->getRoleById($roleId);

        if ($role->slug === 'super-admin') {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Super Admin role cannot be edited.');
        }

        $permissions        = Permission::orderBy('name')->get();
        $groupedPermissions = $this->groupPermissionsByModule($permissions);

        return view('admin.roles.add_role', [
            'title'               => 'Edit Role',
            'role'                => $role,
            'groupedPermissions'  => $groupedPermissions,
            'assigned_permissions'=> $role->permissions->pluck('name')->toArray(),
        ]);
    }

    // ─── Store ───────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        if (!auth('admin')->user()->hasPermissionTo('Roles Create')) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'name'        => ['required', 'string', 'max:180', 'unique:roles,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ], [
            'name.unique' => 'A role with this name already exists.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $this->roleService->createRole($request->only('name', 'description', 'permissions'));

            return response()->json([
                'success'  => 'Role created successfully.',
                'redirect' => route('admin.roles.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => $e->getMessage()]], 500);
        }
    }

    // ─── Update ──────────────────────────────────────────────────────────────
    public function update(Request $request, $roleId)
    {
        if (!auth('admin')->user()->hasPermissionTo('Roles Edit')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $role = $this->roleService->getRoleById($roleId);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => 'Role not found.']], 404);
        }

        if ($role->slug === 'super-admin') {
            return response()->json(['errors' => ['general' => 'Super Admin role cannot be edited.']], 422);
        }

        $validator = Validator::make($request->all(), [
            'name'          => ['required', 'string', 'max:180', 'unique:roles,name,' . $role->id . ',id'],
            'description'   => ['nullable', 'string', 'max:500'],
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ], [
            'name.unique' => 'A role with this name already exists.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $this->roleService->updateRole($roleId, $request->only('name', 'description', 'permissions'));

            return response()->json([
                'success' => 'Role updated successfully.',
                'redirect' => route('admin.roles.index'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => $e->getMessage()]], 500);
        }
    }

    // ─── Destroy ─────────────────────────────────────────────────────────────
    public function destroy(Request $request)
    {
        if (!auth('admin')->user()->hasPermissionTo('Roles Delete')) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'role_id' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $this->roleService->deleteRole($request->role_id);

            return response()->json([
                'success'   => 'Role deleted successfully.',
                'remove_tr' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['general' => $e->getMessage()]], 422);
        }
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────
    private function groupPermissionsByModule($permissions)
    {
        $modules = [
            'Dashboard', 'Staffs', 'Permissions', 'Roles',
            'Account Types', 'Accounts', 'Payment Books', 'Categories', 'Items',
            'Cash Books', 'Companies',
            'Medicine Invoices', 'Feed Invoices', 'Chick Invoices',
            'Murghi Invoices', 'Other Invoices',
            'Stock', 'Expenses', 'Reports',
        ];
        $actions = ['Access', 'Create', 'Edit', 'Delete'];

        $grouped = [];
        foreach ($modules as $module) {
            $grouped[$module] = [];
            foreach ($actions as $action) {
                $permission = $permissions->firstWhere('name', $module . ' ' . $action);
                if ($permission) {
                    $grouped[$module][] = $permission;
                }
            }
        }

        return $grouped;
    }
}
