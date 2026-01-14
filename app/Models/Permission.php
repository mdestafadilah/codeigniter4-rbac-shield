<?php

namespace App\Models;

use CodeIgniter\Model;

class Permission extends Model
{
    protected $table            = 'permissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'display_name', 'description', 'module', 'is_active'];

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
        'name' => 'required|min_length[3]|max_length[50]|is_unique[permissions.name,id,{id}]',
        'display_name' => 'required|min_length[3]|max_length[100]',
        'module' => 'max_length[50]',
        'is_active' => 'in_list[false,true]'
    ];
    protected $validationMessages = [
        'name' => [
            'required' => 'Nama permission harus diisi',
            'min_length' => 'Nama permission minimal 3 karakter',
            'max_length' => 'Nama permission maksimal 50 karakter',
            'is_unique' => 'Nama permission sudah digunakan'
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

    public function getByModule($module = null)
    {
        if ($module) {
            return $this->where('module', $module)
                ->where('is_active', true)
                ->findAll();
        }
        
        return $this->where('is_active', true)->findAll();
    }

    public function getModules()
    {
        return $this->select('module')
            ->where('module IS NOT NULL')
            ->where('module !=', '')
            ->groupBy('module')
            ->findAll();
    }
}
