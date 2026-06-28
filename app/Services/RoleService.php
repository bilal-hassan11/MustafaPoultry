<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Permission;
use App\Services\Slug;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class RoleService
{
    protected $slugService;

    public function __construct(Slug $slugService)
    {
        $this->slugService = $slugService;
    }

    public function getAllRoles()
    {
        return Role::with('permissions')->latest()->get();
    }

    public function getRoleById($id)
    {
        return Role::with('permissions')->hashidFindOrFail($id);
    }

    public function createRole(array $data)
    {
        DB::beginTransaction();

        try {
            $role = Role::create([
                'name' => $data['name'],
                'slug' => $this->slugService->createSlug('roles', $data['name']),
                'description' => Arr::get($data, 'description'),
            ]);

            if (isset($data['permissions']) && count($data['permissions']) > 0) {
                $this->syncPermissions($role, $data['permissions']);
            }

            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateRole($id, array $data)
    {
        DB::beginTransaction();

        try {
            $role = $this->getRoleById($id);
            
            if ($role->slug === 'super-admin') {
                throw new \Exception('Super Admin role cannot be edited');
            }

            $role->update([
                'name' => $data['name'],
                'slug' => $role->slug,
                'description' => Arr::get($data, 'description'),
            ]);

            if (isset($data['permissions'])) {
                $this->syncPermissions($role, $data['permissions']);
            }

            DB::commit();
            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteRole($id)
    {
        $role = $this->getRoleById($id);
        
        if ($role->slug === 'super-admin') {
            throw new \Exception('Super Admin role cannot be deleted');
        }

        $role->permissions()->detach();
        $role->admins()->detach();
        return $role->delete();
    }

    protected function syncPermissions(Role $role, array $permissionNames)
    {
        $permissionIds = [];
        foreach ($permissionNames as $name) {
            $permission = Permission::firstOrCreate(
                ['name' => $name],
                ['slug' => $this->slugService->createSlug('permissions', $name)]
            );
            $permissionIds[] = $permission->id;
        }
        $role->permissions()->sync($permissionIds);
    }
}
