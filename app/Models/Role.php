<?php

namespace App\Models;

use CodeIgniter\Model;

class Role extends Model
{
    protected $table            = 'roles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'display_name', 'description', 'is_active'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[50]|is_unique[roles.name,id,{id}]',
        'display_name' => 'required|min_length[3]|max_length[100]',
        'is_active' => 'in_list[false,true]'
    ];
    protected $validationMessages = [
        'name' => [
            'required' => 'Nama role harus diisi',
            'min_length' => 'Nama role minimal 3 karakter',
            'max_length' => 'Nama role maksimal 50 karakter',
            'is_unique' => 'Nama role sudah digunakan'
        ],
        'display_name' => [
            'required' => 'Nama tampilan harus diisi',
            'min_length' => 'Nama tampilan minimal 3 karakter',
            'max_length' => 'Nama tampilan maksimal 100 karakter'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getPermissions($roleId)
    {
        return $this->db->table('role_permissions rp')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('rp.role_id', $roleId)
            ->where('p.is_active', true)
            ->orderBy('p.module','asc')
            ->get()
            ->getResultArray();
    }

    public function assignPermission($roleId, $permissionId)
    {
        $rolePermissionModel = new RolePermission();
        return $rolePermissionModel->insert([
            'role_id' => $roleId,
            'permission_id' => $permissionId
        ]);
    }

    public function removePermission($roleId, $permissionId)
    {
        $rolePermissionModel = new RolePermission();
        return $rolePermissionModel->where('role_id', $roleId)
            ->where('permission_id', $permissionId)
            ->delete();
    }

    public function syncPermissions($roleId, $permissionIds)
    {
        $rolePermissionModel = new RolePermission();
        
        // Remove existing permissions
        $rolePermissionModel->where('role_id', $roleId)->delete();
        
        // Add new permissions
        if (!empty($permissionIds)) {
            $data = [];
            foreach ($permissionIds as $permissionId) {
                $data[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId
                ];
            }
            return $rolePermissionModel->insertBatch($data);
        }
        
        return true;
    }
}
