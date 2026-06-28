<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissionName = $module . ' ' . $action;
                $existingPermission = Permission::where('name', $permissionName)->first();
                if (!$existingPermission) {
                    Permission::create([
                        'name' => $permissionName,
                        'slug' => $this->slugService->createSlug('permissions', $permissionName)
                    ]);
                }
            }
        }

        // Create Super Admin role
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'Super Admin'],
            [
                'slug' => $this->slugService->createSlug('roles', 'Super Admin'),
                'description' => 'Full system access'
            ]
        );

        // Assign all permissions to Super Admin
        $superAdminRole->permissions()->sync(Permission::pluck('id')->toArray());
    }
}
