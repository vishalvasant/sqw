<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create Permissions
        Permission::create(['name' => 'manage_inventory']);
        Permission::create(['name' => 'manage_fleet']);
        Permission::create(['name' => 'manage_assets']);
        Permission::create(['name' => 'manage_purchases']);
        Permission::create(['name' => 'manage_hr']);
        Permission::create(['name' => 'manage_tasks']);

        // Create Roles
        $adminRole = Role::create(['name' => 'Admin']);
        $adminRole->givePermissionTo(Permission::all());

        $managerRole = Role::create(['name' => 'Manager']);
        $managerRole->givePermissionTo(['manage_inventory', 'manage_fleet', 'manage_assets']);

        $userRole = Role::create(['name' => 'User']);
        $userRole->givePermissionTo(['manage_tasks']);
    }
}

