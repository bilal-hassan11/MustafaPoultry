<?php

namespace App\Permissions;

trait HasPermissionsTrait
{
    public function hasPermissionTo($permission)
    {
        return (bool) $this->hasPermission($permission);
    }

    public function hasRole(...$roles)
    {
        foreach ($roles as $role) {
            if ($this->user_type == $role) {
                return true;
            }
            if ($this->roles->contains('slug', $role)) {
                return true;
            }
        }
        return false;
    }

    protected function hasPermission($permission)
    {
        // Super Admin bypass — full access to everything
        if ($this->user_type == 'admin') {
            return true;
        }

        // Check via roles (eager-load roles with permissions to avoid N+1)
        $roles = $this->relationLoaded('roles')
            ? $this->roles
            : $this->load('roles.permissions')->roles;

        foreach ($roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }

        // Backward compatibility: check direct user_permissions JSON field
        $permissions = collect($this->user_permissions);
        return (bool) $permissions->where('name', $permission)->count();
    }

    public function can($permission, $arguments = [])
    {
        return (bool) $this->hasPermission($permission);
    }

    public function canAny($_permissions, $arguments = [])
    {
        if ($this->user_type == 'admin') {
            return true;
        }

        foreach ($_permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public function cant($permission, $arguments = [])
    {
        if ($this->user_type == 'admin') {
            return false;
        }
        return !$this->hasPermission($permission);
    }

    public function cannot($permission, $arguments = [])
    {
        return $this->cant($permission);
    }
}
