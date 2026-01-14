<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Permission;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionController extends BaseController
{
    protected $permissionModel;

    public function __construct()
    {
        $this->permissionModel = new Permission();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data = [
            'title' => 'Permissions Management',
            'permissions' => $this->permissionModel->orderBy('module', 'ASC')->findAll(),
            'modules' => $this->permissionModel->getModules()
        ];

        return $this->render('permissions/index', $data);
    }

    public function show($id)
    {
        $permission = $this->permissionModel->find($id);
        
        if (!$permission) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Get roles that have this permission
        $roleModel = new \App\Models\Role();
        $roles = $roleModel->select('roles.*')
            ->join('role_permissions', 'role_permissions.role_id = roles.id')
            ->where('role_permissions.permission_id', $id)
            ->findAll();

        $data = [
            'title' => 'Permission Details',
            'permission' => $permission,
            'roles' => $roles
        ];

        return $this->render('permissions/show', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Permission',
            'modules' => $this->permissionModel->getModules()
        ];

        return $this->render('permissions/create', $data);
    }

    public function store()
    {
        // Manual validation for create
        $rules = [
            'name' => 'required|min_length[3]|max_length[50]|is_unique[permissions.name]',
            'display_name' => 'required|min_length[3]|max_length[100]',
            'module' => 'required|max_length[50]',
            'description' => 'max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return back_with_validation_errors($this->validator);
        }

        // Handle custom module
        $module = $this->request->getPost('module');
        if ($module === 'custom') {
            $module = $this->request->getPost('custom_module_name');
            if (empty($module)) {
                set_error_message('Nama module custom harus diisi');
                return redirect()->back()->withInput();
            }
        }

        // Generate name based on module and action
        $action = $this->request->getPost('action');
        $name = $this->request->getPost('name');
        if (empty($name) && !empty($module) && !empty($action)) {
            $name = strtolower($module) . '.' . $action;
        }

        $data = [
            'name' => $name,
            'display_name' => $this->request->getPost('display_name'),
            'description' => $this->request->getPost('description'),
            'module' => $module,
            'is_active' => true
        ];

        try {
            if ($this->permissionModel->skipValidation(true)->save($data)) {
                return redirect_with_success('/permissions', 'Permission berhasil dibuat');
            } else {
                set_error_message('Gagal membuat permission. Silakan coba lagi.');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            handle_database_exception($e, 'pembuatan permission');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $permission = $this->permissionModel->find($id);
        
        if (!$permission) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $data = [
            'title' => 'Edit Permission',
            'permission' => $permission,
            'modules' => $this->permissionModel->getModules()
        ];

        return $this->render('permissions/edit', $data);
    }

    public function update($id)
    {
        $permission = $this->permissionModel->find($id);
        
        if (!$permission) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Manual validation with proper ID exclusion
        $rules = [
            'name' => 'required|min_length[3]|max_length[50]|is_unique[permissions.name,id,' . $id . ']',
            'display_name' => 'required|min_length[3]|max_length[100]',
            'module' => 'required|max_length[50]',
            'description' => 'max_length[255]'
        ];

        if (!$this->validate($rules)) {
            return back_with_validation_errors($this->validator);
        }

        // Handle custom module
        $module = $this->request->getPost('module');
        if ($module === 'custom') {
            $module = $this->request->getPost('custom_module_name');
        }

        // Generate name based on module and action if needed
        $action = $this->request->getPost('action');
        $name = $this->request->getPost('name');
        if (empty($name) && !empty($module) && !empty($action)) {
            $name = strtolower($module) . '.' . $action;
        }

        $data = [
            'name' => $name,
            'display_name' => $this->request->getPost('display_name'),
            'description' => $this->request->getPost('description'),
            'module' => $module
        ];

        try {
            if ($this->permissionModel->skipValidation(true)->update($id, $data)) {
                return redirect_with_success('/permissions', 'Permission berhasil diperbarui');
            } else {
                set_error_message('Gagal memperbarui permission. Silakan coba lagi.');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            handle_database_exception($e, 'pembaruan permission');
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        $permission = $this->permissionModel->find($id);
        
        if (!$permission) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Check if permission is being used by roles
        $rolePermissionModel = new \App\Models\RolePermission();
        $usageCount = $rolePermissionModel->where('permission_id', $id)->countAllResults();
        
        if ($usageCount > 0) {
            set_error_message('Tidak dapat menghapus permission. Permission ini digunakan oleh ' . $usageCount . ' role');
            return redirect()->to('/permissions');
        }

        try {
            if ($this->permissionModel->delete($id)) {
                return redirect_with_success('/permissions', 'Permission berhasil dihapus');
            } else {
                handle_model_errors($this->permissionModel, 'penghapusan permission');
            }
        } catch (\Exception $e) {
            handle_database_exception($e, 'penghapusan permission');
        }

        return redirect()->to('/permissions');
    }

    public function toggle($id)
    {
        $permission = $this->permissionModel->find($id);
        
        if (!$permission) {
            return $this->response->setJSON(['success' => false, 'message' => 'Permission not found']);
        }

        $newStatus = $permission['is_active'] ? false : true;
        
        if ($this->permissionModel->update($id, ['is_active' => $newStatus])) {
            $message = $newStatus ? 'Permission activated successfully' : 'Permission deactivated successfully';
            return $this->response->setJSON(['success' => true, 'message' => $message, 'status' => $newStatus]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update permission status']);
        }
    }
}
