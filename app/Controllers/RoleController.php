<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Role;
use App\Models\Permission;
use CodeIgniter\HTTP\ResponseInterface;

class RoleController extends BaseController
{
    protected $roleModel;
    protected $permissionModel;

    public function __construct()
    {
        $this->roleModel = new Role();
        $this->permissionModel = new Permission();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data = [
            'title' => 'Roles Management',
            'roles' => $this->roleModel->findAll()
        ]; //exit(dd($data));

        return $this->render('roles/index', $data);
    }

    public function show($id)
    {
        $role = $this->roleModel->find($id);
        
        if (!$role) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Get user count for this role
        $userModel = new \App\Models\UserModel();
        $userCount = $userModel->where('role_id', $id)->countAllResults();

        $data = [
            'title' => 'Role Details',
            'role' => $role,
            'permissions' => $this->roleModel->getPermissions($id),
            'userCount' => $userCount
        ];

        return $this->render('roles/show', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Role',
            'permissions' => $this->permissionModel->where('is_active', true)->findAll(),
            'modules' => $this->permissionModel->getModules()
        ];

        return $this->render('roles/create', $data);
    }

    public function store()
    {
        // Manual validation for create
        $rules = [
            'name' => 'required|min_length[3]|max_length[50]|is_unique[roles.name]',
            'display_name' => 'required|min_length[3]|max_length[100]',
            'description' => 'max_length[255]',
            'is_active' => 'in_list[false,true]'
        ];

        if (!$this->validate($rules)) {
            return back_with_validation_errors($this->validator);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'display_name' => $this->request->getPost('display_name'),
            'description' => $this->request->getPost('description'),
            'is_active' => $this->request->getPost('is_active') ?? true
        ];

        try {
            $roleId = $this->roleModel->skipValidation(true)->insert($data);
            
            if ($roleId) {
                $permissions = $this->request->getPost('permissions') ?? [];
                if (!empty($permissions)) {
                    $this->roleModel->syncPermissions($roleId, $permissions);
                }
                
                return redirect_with_success('/roles', 'Role berhasil dibuat');
            } else {
                set_error_message('Gagal membuat role. Silakan coba lagi.');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            handle_database_exception($e, 'pembuatan role');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $role = $this->roleModel->find($id);
        
        if (!$role) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $rolePermissions = $this->roleModel->getPermissions($id);
        $assignedPermissions = array_column($rolePermissions, 'id');

        $data = [
            'title' => 'Edit Role',
            'role' => $role,
            'permissions' => $this->permissionModel->where('is_active', true)->findAll(),
            'assignedPermissions' => $assignedPermissions,
            'modules' => $this->permissionModel->getModules()
        ];

        return $this->render('roles/edit', $data);
    }

    public function update($id)
    {
        $role = $this->roleModel->find($id);
        
        if (!$role) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Manual validation with proper ID exclusion
        $rules = [
            'name' => 'required|min_length[3]|max_length[50]|is_unique[roles.name,id,' . $id . ']',
            'display_name' => 'required|min_length[3]|max_length[100]',
            'description' => 'max_length[255]',
            'is_active' => 'in_list[false,true]'
        ];

        if (!$this->validate($rules)) {
            return back_with_validation_errors($this->validator);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'display_name' => $this->request->getPost('display_name'),
            'description' => $this->request->getPost('description'),
            'is_active' => $this->request->getPost('is_active') ?? true
        ];

        try {
            if ($this->roleModel->skipValidation(true)->update($id, $data)) {
                $permissions = $this->request->getPost('permissions') ?? [];
                $this->roleModel->syncPermissions($id, $permissions);
                
                return redirect_with_success('/roles', 'Role berhasil diperbarui');
            } else {
                set_error_message('Gagal memperbarui role. Silakan coba lagi.');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            handle_database_exception($e, 'pembaruan role');
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        $role = $this->roleModel->find($id);
        
        if (!$role) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $userModel = new \App\Models\UserModel();
        $usersCount = $userModel->where('role_id', $id)->countAllResults();
        
        if ($usersCount > 0) {
            set_error_message('Tidak dapat menghapus role. Role ini digunakan oleh ' . $usersCount . ' user');
            return redirect()->to('/roles');
        }

        try {
            if ($this->roleModel->delete($id)) {
                return redirect_with_success('/roles', 'Role berhasil dihapus');
            } else {
                handle_model_errors($this->roleModel, 'penghapusan role');
            }
        } catch (\Exception $e) {
            handle_database_exception($e, 'penghapusan role');
        }

        return redirect()->to('/roles');
    }

    public function toggle($id)
    {
        $role = $this->roleModel->find($id);
        
        if (!$role) {
            return $this->response->setJSON(['success' => false, 'message' => 'Role not found']);
        }

        $newStatus = $role['is_active'] ? false : true;
        
        if ($this->roleModel->update($id, ['is_active' => $newStatus])) {
            $message = $newStatus ? 'Role activated successfully' : 'Role deactivated successfully';
            return $this->response->setJSON(['success' => true, 'message' => $message, 'status' => $newStatus]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update role status']);
        }
    }
}
