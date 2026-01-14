<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table            = 'menus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'parent_id',
        'label',
        'url',
        'icon',
        'permission',
        'type',
        'order_position',
        'is_active'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_active' => 'boolean',
        'order_position' => 'integer',
        'parent_id' => '?integer', // Nullable integer
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'label' => 'required|max_length[255]',
        'type'  => 'required|in_list[section,group,item]',
        'order_position' => 'permit_empty|integer',
        'parent_id' => 'permit_empty|integer', // Allow empty/null
    ];
    protected $validationMessages   = [
        'label' => [
            'required' => 'Label menu harus diisi',
            'max_length' => 'Label menu maksimal 255 karakter'
        ],
        'type' => [
            'required' => 'Tipe menu harus diisi',
            'in_list' => 'Tipe menu harus salah satu dari: section, group, item'
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

    /**
     * Get all menus hierarchically
     */
    public function getMenuTree()
    {
        $menus = $this->where('is_active', true)
                      ->orderBy('order_position', 'ASC')
                      ->findAll();
        
        return $this->buildTree($menus);
    }

    /**
     * Build hierarchical tree from flat array
     */
    private function buildTree($elements, $parentId = null)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }

    /**
     * Get menus by type
     */
    public function getMenusByType($type)
    {
        return $this->where('type', $type)
                    ->where('is_active', true)
                    ->orderBy('order_position', 'ASC')
                    ->findAll();
    }

    /**
     * Get menu items by parent ID
     */
    public function getChildMenus($parentId)
    {
        return $this->where('parent_id', $parentId)
                    ->where('is_active', true)
                    ->orderBy('order_position', 'ASC')
                    ->findAll();
    }
}
