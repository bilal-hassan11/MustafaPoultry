<?php

namespace App\Permissions;

use Illuminate\Contracts\Auth\Access\Gate;

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
    if ($this->user_type == 'admin') {
      return true;
    }

    // Check via roles
    foreach ($this->roles as $role) {
      if ($role->hasPermission($permission)) {
        return true;
      }
    }

    // Backward compatibility: check direct user permissions
    $permissions = collect($this->user_permissions);
    return (bool) $permissions->where('name', $permission)->count();
  }

  public function can($permission, $arguments = [])
  {
    return (bool) $this->hasPermission($permission, $arguments);
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
    return !$this->hasPermission($permission, $arguments);
  }

  public function cannot($permission, $arguments = [])
  {
    return $this->cant($permission, $arguments);
  }
}
