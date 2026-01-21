<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{

    protected function initialize(): void
    {
        parent::initialize();

        $this->allowedFields = [
            ...$this->allowedFields,
            'first_name', // Added
            'last_name',  // Added
        ];
    }

    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    // protected $returnType       = 'array'; // Fix: Shield expects Entity, not array

    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['username', 'email', 'role_id', 'active', 'last_login', 'status'];

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
    protected $validationRules      = [
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'status'   => 'permit_empty|in_list[active,inactive]'
    ];
    protected $validationMessages   = [
        'username' => [
            'required' => 'Username harus diisi',
            'min_length' => 'Username minimal 3 karakter',
            'max_length' => 'Username maksimal 100 karakter',
            'is_unique' => 'Username sudah digunakan'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['setDefaultAttributes']; 
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function setDefaultAttributes(array $data)
    {
        // 1. Set default role name - REMOVED: Column 'role' does not exist in users table
        
        // 2. Set default role_id if missing
        if (!isset($data['data']['role_id'])) {
             $roleModel = model('App\Models\Role');
             $userRole = $roleModel->where('name', 'user')->first();
             if ($userRole) {
                 $data['data']['role_id'] = $userRole['id'];
             }
        }
        
        // 3. Set active = 1 (User request, int for Postgres smallint)
        if (!isset($data['data']['active'])) {
             $data['data']['active'] = 1;
        }

        // 4. Capture Email from Request if missing (Shield doesn't pass it to User Entity by default)
        if (!isset($data['data']['email'])) {
            $request = service('request');
            $email = $request->getPost('email');
            if ($email) {
                $data['data']['email'] = $email;
            }
        }

        // 5. Set default status
        if (!isset($data['data']['status'])) {
             $data['data']['status'] = 'active'; // Default status for registration
        }

        return $data;
    }

    public function getUserWithRole($userId)
    {
        return $this->select('users.*, roles.name as role_name, roles.display_name as role_display_name, auth_identities.secret as email')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = \'email_password\'', 'left')
            ->where('users.id', $userId)
            ->asArray() // Fix: Return array to maintain compatibility with views
            ->first();
    }

    public function getUserPermissions($userId)
    {
        return $this->db->table('users u')
            ->join('roles r', 'r.id = u.role_id')
            ->join('role_permissions rp', 'rp.role_id = r.id')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('u.id', $userId)
            ->where('u.active', true)
            ->where('r.is_active', true)
            ->where('p.is_active', true)
            ->select('p.name, p.display_name, p.module')
            ->get()
            ->getResultArray();
    }

    public function hasPermission($userId, $permission)
    {
        $count = $this->db->table('users u')
            ->join('roles r', 'r.id = u.role_id')
            ->join('role_permissions rp', 'rp.role_id = r.id')
            ->join('permissions p', 'p.id = rp.permission_id')
            ->where('u.id', $userId)
            ->where('p.name', $permission)
            ->where('u.active', true)
            ->where('r.is_active', true)
            ->where('p.is_active', true)
            ->countAllResults();
            
        return $count > 0;
    }

    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function updateRoleId($userId,$roleId)
    {
        return $this->update($userId, ['role_id' => $roleId, 'active' => true]); //exit(dd($this->db->getLastQuery()->getQuery()));
    }

    public function findUserByEmailAddress(string $emailAddress)
    {
        $user = $this->asArray()->where(['email' => $emailAddress])->first();

        if (!$user) {
            throw new \Exception('User does not exist for specified email address');
        }

        return $user;
    }
}
