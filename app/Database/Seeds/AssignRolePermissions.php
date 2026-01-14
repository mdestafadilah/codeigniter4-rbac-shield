<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AssignRolePermissions extends Seeder
{
    public function run()
    {
        $roleModel = new \App\Models\Role();
        $permissionModel = new \App\Models\Permission();
        
        // Clear existing role permissions
        $this->db->table('role_permissions')->truncate();
        
        $superAdminRole = $roleModel->where('name', 'super_admin')->first();
        $adminRole = $roleModel->where('name', 'admin')->first();
        $operatorRole = $roleModel->where('name', 'operator')->first();
        $userRole = $roleModel->where('name', 'user')->first();
        
        $allPermissions = $permissionModel->findAll();
        $allPermissionIds = array_column($allPermissions, 'id');
        
        // Super Admin gets all permissions
        if ($superAdminRole) {
            $data = [];
            foreach ($allPermissionIds as $permissionId) {
                $data[] = [
                    'role_id' => $superAdminRole['id'],
                    'permission_id' => $permissionId,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            $this->db->table('role_permissions')->insertBatch($data);
        }
        
        // Admin gets most permissions
        if ($adminRole) {
            $data = [];
            foreach ($allPermissionIds as $permissionId) {
                $data[] = [
                    'role_id' => $adminRole['id'],
                    'permission_id' => $permissionId,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            $this->db->table('role_permissions')->insertBatch($data);
        }
        
        // Operator gets mahasiswa and dashboard permissions
        if ($operatorRole) {
            $data = [];
            foreach ($allPermissions as $permission) {
                if (in_array($permission['module'], ['mahasiswa', 'dashboard', 'reports'])) {
                    $data[] = [
                        'role_id' => $operatorRole['id'],
                        'permission_id' => $permission['id'],
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }
            if (!empty($data)) {
                $this->db->table('role_permissions')->insertBatch($data);
            }
        }
        
        // User gets basic permissions
        if ($userRole) {
            $data = [];
            foreach ($allPermissions as $permission) {
                if ($permission['name'] === 'dashboard.view' || 
                    $permission['name'] === 'mahasiswa.view' ||
                    $permission['name'] === 'reports.view') {
                    $data[] = [
                        'role_id' => $userRole['id'],
                        'permission_id' => $permission['id'],
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }
            if (!empty($data)) {
                $this->db->table('role_permissions')->insertBatch($data);
            }
        }
    }
}
