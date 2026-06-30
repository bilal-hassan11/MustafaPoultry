<?php

namespace App\Models;

class Role extends BaseModel
{
    protected $fillable = ['name', 'slug', 'description'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_roles');
    }

    public function hasPermission($permission)
    {
        // Support both permission name and slug lookups
        return $this->permissions->contains('name', $permission)
            || $this->permissions->contains('slug', $permission);
    }
}
