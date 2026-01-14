<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FixAllUsersSeeder extends Seeder
{
    public function run()
    {
        $userModel = new \App\Models\UserModel();
        $roleModel = new \App\Models\Role();
        
        // Get all roles
        $superAdminRole = $roleModel->where('name', 'super_admin')->first();
        $adminRole = $roleModel->where('name', 'admin')->first();
        $userRole = $roleModel->where('name', 'user')->first();
        
        // Get all users that don't have role_id set
        $users = $userModel->where('role_id', null)->findAll();
        
        foreach ($users as $user) {
            $roleId = null;
            
            // Assign role based on legacy role field or username
            if ($user['username'] === 'admin' || $user['role'] === 'admin') {
                $roleId = $superAdminRole['id'] ?? $adminRole['id'];
            } elseif ($user['role'] === 'user') {
                $roleId = $userRole['id'];
            } else {
                $roleId = $userRole['id']; // Default to user role
            }
            
            if ($roleId) {
                $userModel->update($user['id'], [
                    'role_id' => $roleId,
                    'is_active' => true
                ]);
                
                echo "User {$user['username']} updated with role ID {$roleId}\n";
            }
        }
        
        echo "All users fixed successfully!\n";
    }
}
