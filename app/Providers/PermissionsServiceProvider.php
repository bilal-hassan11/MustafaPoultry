<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PermissionsServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Gate::before — super admin (user_type = 'admin') bypasses all gate checks
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'getIsAdminAttribute') && $user->user_type === 'admin') {
                return true;
            }
        });

        // Register a Gate ability for every permission using BOTH its name and slug.
        // Blades use @can('Staffs Create') — that matches by name.
        // Controllers use hasPermissionTo('Staffs Create') — that goes through the trait.
        // Both paths are covered here.
        try {
            Permission::all(['id', 'name', 'slug'])->each(function ($permission) {
                // Define by permission name  (e.g. "Staffs Create")
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasPermissionTo($permission->name);
                });

                // Also define by slug (e.g. "staffs-create") for any legacy checks
                if ($permission->slug && $permission->slug !== $permission->name) {
                    Gate::define($permission->slug, function ($user) use ($permission) {
                        return $user->hasPermissionTo($permission->name);
                    });
                }
            });
        } catch (\Exception $e) {
            // Silently skip if DB is not ready (e.g. during migrations)
            report($e);
        }

        // Blade @roles('admin') directive
        Blade::if('roles', function ($value) {
            return auth('admin')->check() && auth('admin')->user()->hasRole($value);
        });
    }
}
