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
            'email' => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'role_id' => 'required|integer',
            'is_active' => 'in_list[false,true]'
        ];

        if (!$this->validate($rules)) {
            return back_with_validation_errors($this->validator);
        }

        // Create User first
        $userData = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role_id'  => $this->request->getPost('role_id'),
            'active'   => $this->request->getPost('is_active') ? 1 : 0,
            'status'   => $this->request->getPost('is_active') ? 'active' : 'inactive',
        ];

        $user = new \CodeIgniter\Shield\Entities\User($userData);
        
        try {
            if ($this->userModel->save($user)) {
                // Get the inserted user with ID
                $user = $this->userModel->findById($this->userModel->getInsertID());

                // Create Email Identity
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');
                
                $user->createEmailIdentity([
                    'email'    => $email,
                    'password' => $password,
                ]);

                // Also create/update group if needed, though role_id is custom here
                
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

        // Get email identity
        $identity = $user->getEmailIdentity();
        $user->email = $identity ? $identity->secret : '';

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'roles' => $this->roleModel->where('is_active', true)->findAll()
        ];

        return $this->render('users/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        // Manual validation with proper ID exclusion
        // Note: Check auth_identities for email uniqueness excluding this user's identity
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,' . $id . ']',
            'email' => 'required|valid_email', 
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

        // Check email uniqueness manually since is_unique doesn't support complex joins well enough here for identities
        $email = $this->request->getPost('email');
        $identityModel = model('CodeIgniter\Shield\Models\UserIdentityModel');
        $existingIdentity = $identityModel->where('secret', $email)
                                          ->where('type', 'email_password')
                                          ->where('user_id !=', $id)
                                          ->first();
        if ($existingIdentity) {
            return redirect()->back()->withInput()->with('errors', ['email' => 'Email sudah digunakan oleh user lain']);
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email'    => $email,
            'role_id'  => $this->request->getPost('role_id'),
            'active'   => $this->request->getPost('is_active') ? 1 : 0,
            'status'   => $this->request->getPost('is_active') ? 'active' : 'inactive',
        ];

        try {
            // Update User
            $this->userModel->update($id, $userData);

            // Update Email/Password Identity
            $password = $this->request->getPost('password');
            $credentials = ['email' => $email];
            if (!empty($password)) {
                $credentials['password'] = $password;
            }
            
            // Check if identity exists
            $identity = $user->getEmailIdentity();
            if ($identity) {
                // Update credentials not directly supported via simple method on User entity for updating *secret* (email) easily
                // We typically delete and recreate, or update the identity record directly.
                // Shield User Entity doesn't expose updateIdentity easily?
                // Let's use the IdentityModel directly.
                
                $dataToUpdate = ['secret' => $email];
                if (!empty($password)) {
                    $dataToUpdate['secret2'] = password_hash($password, PASSWORD_DEFAULT);
                }
                
                $identityModel->update($identity->id, $dataToUpdate);
            } else {
                // Create if missing
                $user->createEmailIdentity([
                    'email' => $email,
                    'password' => $password ?? 'DefaultPass123!', // Should force reset or require password
                ]);
            }

            return redirect_with_success('/users', 'Data user berhasil diperbarui');

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
