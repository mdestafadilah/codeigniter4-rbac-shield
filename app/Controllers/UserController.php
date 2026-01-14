<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\Role;
use CodeIgniter\HTTP\ResponseInterface;

class UserController extends BaseController
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new Role();
        helper(['form', 'url']);
    }

    public function index()
    {
        $users = $this->userModel->select('users.*, roles.name as role_name, roles.display_name as role_display_name')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->findAll();

        $data = [
            'title' => 'Users Management',
            'users' => $users
        ];

        return $this->render('users/index', $data);
    }

    public function show($id)
    {
        $user = $this->userModel->getUserWithRole($id);
        
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $permissions = $this->userModel->getUserPermissions($id);

        $data = [
            'title' => 'User Details',
            'user' => $user,
            'permissions' => $permissions
        ];

        return $this->render('users/show', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create User',
            'roles' => $this->roleModel->where('is_active', true)->findAll()
        ];

        return $this->render('users/create', $data);
    }

    public function store()
    {
        // Manual validation for create
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'role_id' => 'required|integer',
            'is_active' => 'in_list[false,true]'
        ];

        if (!$this->validate($rules)) {
            return back_with_validation_errors($this->validator);
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role' => 'user', // Keep legacy role field
            'role_id' => $this->request->getPost('role_id'),
            'is_active' => $this->request->getPost('is_active') ?? true
        ];

        try {
            // Use skipValidation to avoid double validation
            if ($this->userModel->skipValidation(true)->save($data)) {
                return redirect_with_success('/users', 'User berhasil dibuat');
            } else {
                set_error_message('Gagal membuat user. Silakan coba lagi.');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            handle_database_exception($e, 'pembuatan user');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => $this->roleModel->where('is_active', true)->findAll()
        ];

        return $this->render('users/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Manual validation with proper ID exclusion
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,' . $id . ']',
            'email' => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
            'role_id' => 'required|integer',
            'is_active' => 'in_list[false,true]'
        ];

        // Add password validation if provided
        if (!empty($this->request->getPost('password'))) {
            $rules['password'] = 'required|min_length[6]';
            $rules['confirm_password'] = 'required|matches[password]';
        }

        if (!$this->validate($rules)) {
            return back_with_validation_errors($this->validator);
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'role_id' => $this->request->getPost('role_id'),
            'is_active' => $this->request->getPost('is_active') ?? true
        ];

        // Only update password if provided
        if (!empty($this->request->getPost('password'))) {
            $data['password'] = $this->request->getPost('password');
        }

        try {
            // Use skipValidation to avoid double validation
            if ($this->userModel->skipValidation(true)->update($id, $data)) {
                return redirect_with_success('/users', 'Data user berhasil diperbarui');
            } else {
                set_error_message('Gagal memperbarui user. Silakan coba lagi.');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            handle_database_exception($e, 'pembaruan user');
            return redirect()->back()->withInput();
        }
    }

    public function delete($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Prevent deleting current user
        if ($id == session()->get('user_id')) {
            session()->setFlashdata('permission_denied', 'Anda tidak dapat menghapus akun Anda sendiri');
            return redirect()->to('/users');
        }

        // Prevent deleting super admin
        if ($user['username'] === 'admin' || $user['role'] === 'super_admin') {
            session()->setFlashdata('permission_denied', 'User super admin tidak dapat dihapus');
            return redirect()->to('/users');
        }

        try {
            if ($this->userModel->delete($id)) {
                session()->setFlashdata('success', 'User berhasil dihapus');
            } else {
                $errors = $this->userModel->errors();
                if (!empty($errors)) {
                    $errorMessage = 'Gagal menghapus user:<br><ul>';
                    foreach ($errors as $error) {
                        $errorMessage .= '<li>' . $error . '</li>';
                    }
                    $errorMessage .= '</ul>';
                    session()->setFlashdata('error', $errorMessage);
                } else {
                    session()->setFlashdata('error', 'Gagal menghapus user. Silakan coba lagi.');
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error deleting user: ' . $e->getMessage());
            if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
                session()->setFlashdata('error', 'User tidak dapat dihapus karena masih memiliki data terkait di sistem');
            } else {
                session()->setFlashdata('db_error', 'Terjadi kesalahan database: ' . $e->getMessage());
            }
        }

        return redirect()->to('/users');
    }

    public function toggle($id)
    {
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        // Prevent deactivating current user
        if ($id == session()->get('user_id')) {
            return $this->response->setJSON(['success' => false, 'message' => 'You cannot deactivate your own account']);
        }

        // Prevent deactivating super admin
        if ($user['username'] === 'admin' || $user['role'] === 'super_admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Cannot deactivate super admin user']);
        }

        $newStatus = $user['is_active'] ? false : true;
        
        if ($this->userModel->update($id, ['is_active' => $newStatus])) {
            $message = $newStatus ? 'User activated successfully' : 'User deactivated successfully';
            return $this->response->setJSON(['success' => true, 'message' => $message, 'status' => $newStatus]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to update user status']);
        }
    }
}
