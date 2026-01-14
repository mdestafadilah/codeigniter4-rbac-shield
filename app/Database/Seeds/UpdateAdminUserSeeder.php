<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateAdminUserSeeder extends Seeder
{
    public function run()
    {
        $userModel = new \App\Models\UserModel();
        $roleModel = new \App\Models\Role();
        
        // Get admin user
        $adminUser = $userModel->where('username', 'admin')->first();
        
        if ($adminUser) {
            // Get super_admin role
            $superAdminRole = $roleModel->where('name', 'super_admin')->first();
            
            if ($superAdminRole) {
                // Update admin user with super_admin role
                $userModel->update($adminUser['id'], [
                    'role_id' => $superAdminRole['id'],
                    'is_active' => true
                ]);
                
                echo "Admin user updated with super_admin role successfully!\n";
            } else {
                echo "Super admin role not found!\n";
            }
        } else {
            echo "Admin user not found!\n";
        }
        
        // Also update user1 if exists
        $user1 = $userModel->where('username', 'user1')->first();
        
        if ($user1) {
            $userRole = $roleModel->where('name', 'user')->first();
            
            if ($userRole) {
                $userModel->update($user1['id'], [
                    'role_id' => $userRole['id'],
                    'is_active' => true
                ]);
                
                echo "User1 updated with user role successfully!\n";
            }
        }
    }
}
