<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissionModel = new \App\Models\Permission();
        
        $permissions = [
            // User Management
            ['name' => 'users.view', 'display_name' => 'View Users', 'description' => 'Can view users list', 'module' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Create User', 'description' => 'Can create new users', 'module' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit User', 'description' => 'Can edit user information', 'module' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete User', 'description' => 'Can delete users', 'module' => 'users'],
            
            // Role Management
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'description' => 'Can view roles list', 'module' => 'roles'],
            ['name' => 'roles.create', 'display_name' => 'Create Role', 'description' => 'Can create new roles', 'module' => 'roles'],
            ['name' => 'roles.edit', 'display_name' => 'Edit Role', 'description' => 'Can edit role information', 'module' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Role', 'description' => 'Can delete roles', 'module' => 'roles'],
            
            // Permission Management
            ['name' => 'permissions.view', 'display_name' => 'View Permissions', 'description' => 'Can view permissions list', 'module' => 'permissions'],
            ['name' => 'permissions.create', 'display_name' => 'Create Permission', 'description' => 'Can create new permissions', 'module' => 'permissions'],
            ['name' => 'permissions.edit', 'display_name' => 'Edit Permission', 'description' => 'Can edit permission information', 'module' => 'permissions'],
            ['name' => 'permissions.delete', 'display_name' => 'Delete Permission', 'description' => 'Can delete permissions', 'module' => 'permissions'],
            
            // Mahasiswa Management
            ['name' => 'mahasiswa.view', 'display_name' => 'View Mahasiswa', 'description' => 'Can view mahasiswa list', 'module' => 'mahasiswa'],
            ['name' => 'mahasiswa.create', 'display_name' => 'Create Mahasiswa', 'description' => 'Can create new mahasiswa', 'module' => 'mahasiswa'],
            ['name' => 'mahasiswa.edit', 'display_name' => 'Edit Mahasiswa', 'description' => 'Can edit mahasiswa information', 'module' => 'mahasiswa'],
            ['name' => 'mahasiswa.delete', 'display_name' => 'Delete Mahasiswa', 'description' => 'Can delete mahasiswa', 'module' => 'mahasiswa'],
            
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'description' => 'Can access dashboard', 'module' => 'dashboard'],
            
            // Reports
            ['name' => 'reports.view', 'display_name' => 'View Reports', 'description' => 'Can view reports', 'module' => 'reports'],
            ['name' => 'reports.export', 'display_name' => 'Export Reports', 'description' => 'Can export reports', 'module' => 'reports'],
        ];

        // Insert each permission individually
        foreach ($permissions as $permission) {
            $permissionModel->insert($permission);
        }
    }
}
