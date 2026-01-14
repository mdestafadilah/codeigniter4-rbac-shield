<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = auth()->getProvider();
        $roleModel = new \App\Models\Role();
        $userModel = new \App\Models\UserModel();
        
        // Get roles first
        $superAdminRole = $roleModel->where('name', 'super_admin')->first();
        $userRole = $roleModel->where('name', 'user')->first();
        
        // Create admin user if it doesn't exist
        $admin = $userModel->where('username', 'admin')->first();
        if (!$admin) {
            $adminUser = new User([
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => 'admin123',
            ]);
            $users->save($adminUser);
            
            // Get the created user
            $admin = $userModel->where('username', 'admin')->first();
        }
        
        // Assign role to admin
        if ($admin && $superAdminRole) {
            $userModel->update($admin['id'], [
                'role_id' => $superAdminRole['id'],
                'is_active' => 1
            ]);
        }
        
        // Create regular user if it doesn't exist
        $user = $userModel->where('username', 'user1')->first();
        if (!$user) {
            $regularUser = new User([
                'username' => 'user1',
                'email' => 'user1@example.com',
                'password' => 'user123',
            ]);
            $users->save($regularUser);
            
            // Get the created user
            $user = $userModel->where('username', 'user1')->first();
        }
        
        // Assign role to regular user  
        if ($user && $userRole) {
            $userModel->update($user['id'], [
                'role_id' => $userRole['id'],
                'is_active' => 1
            ]);
        }
    }
}
