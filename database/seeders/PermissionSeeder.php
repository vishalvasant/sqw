<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permissions = [
            'manage users', 'manage roles', 'manage permissions',
            'manage products', 'manage orders', 'manage reports'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Admin Role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        
        // Assign all permissions to Admin Role
        $adminRole->syncPermissions(Permission::all());
    }
}
