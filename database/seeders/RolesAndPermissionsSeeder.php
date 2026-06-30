<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use App\Services\Slug;

class RolesAndPermissionsSeeder extends Seeder
{
    protected $slugService;

    public function __construct(Slug $slugService)
    {
        $this->slugService = $slugService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All modules present in the application
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
            'Reports',
        ];

        $actions = ['Access', 'Create', 'Edit', 'Delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissionName = $module . ' ' . $action;
                $existingPermission = Permission::where('name', $permissionName)->first();
                if (!$existingPermission) {
                    Permission::create([
                        'name' => $permissionName,
                        'slug' => $this->slugService->createSlug('permissions', $permissionName),
                    ]);
                }
            }
        }

        // Create Super Admin role with all permissions
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'Super Admin'],
            [
                'slug'        => 'super-admin',
                'description' => 'Full system access',
            ]
        );

        // Sync ALL permissions to Super Admin role
        $superAdminRole->permissions()->sync(Permission::pluck('id')->toArray());

        // Assign Super Admin role to the admin user (user_type = 'admin')
        $adminUser = Admin::where('user_type', 'admin')->first();
        if ($adminUser) {
            $adminUser->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }
    }
}
