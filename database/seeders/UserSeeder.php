<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'], // Check by email
            [
                'name' => 'Admin User',
                'password' => bcrypt('password123'), // Default password
            ]
        );
        $admin->assignRole('Admin');

        // Create Manager User
        $manager = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager User',
                'password' => bcrypt('password123'),
            ]
        );
        $manager->assignRole('Manager');

        // Create Employee User
        $employee = User::firstOrCreate(
            ['email' => 'employee@example.com'],
            [
                'name' => 'Employee User',
                'password' => bcrypt('password123'),
            ]
        );
        $employee->assignRole('Employee');

        // Create Viewer User
        $viewer = User::firstOrCreate(
            ['email' => 'viewer@example.com'],
            [
                'name' => 'Viewer User',
                'password' => bcrypt('password123'),
            ]
        );
        $viewer->assignRole('Viewer');

        $this->command->info('Users seeded successfully.');
    }
}
