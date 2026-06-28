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
        return $this->permissions->contains('slug', $permission);
    }
}
