<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Entities\User;

class AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper('form');
    }

    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            // Validate input
            $validation = \Config\Services::validation();
            $validation->setRules([
                'username' => 'required|min_length[3]',
                'password' => 'required|min_length[6]',
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                session()->setFlashdata('error', 'Username dan password harus diisi dengan benar');
                return $this->render('auth/login');
            }

            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            // Optimized query - removed unnecessary auth_logins join
            $user = $this->userModel
                        ->join('auth_identities','auth_identities.user_id = users.id','left')
                        ->where('identifier', $username)->first();

            if (!$user) {
                session()->setFlashdata('error', 'Username atau password salah');
                return $this->render('auth/login');
            }

            // Check if user is active
            if ($user['active'] != 1 && $user['active'] != true) {
                session()->setFlashdata('error', 'Akun Anda tidak aktif. Silakan hubungi administrator');
                return $this->render('auth/login');
            }

            // Verify password
            if (!password_verify($password, $user['secret'])) {
                session()->setFlashdata('error', 'Username atau password salah');
                return $this->render('auth/login');
            }

            // Get user with role information
            $userWithRole = $this->userModel->getUserWithRole($user['id']);
            
            // Update last login
            $this->userModel->updateLastLogin($user['id']);
            
            session()->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'],
                'role_id' => $user['role_id'],
                'role_name' => $userWithRole['role_name'] ?? $user['role'],
                'logged_in' => true
            ]);
            return redirect()->to('/dashboard');
        }

        return $this->render('auth/login');
    }

    public function register()
    {
        if ($this->request->getMethod() === 'POST') {

            // Field Default
            $username = $this->request->getPost('username');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            // Use Shield's user provider
            $users = auth()->getProvider(); //exit(dd($users));
            
            // Create new Shield user entity
            $user = new User([
                'username' => $username,
                'email' => $email,
                'password' => $password,
            ]);

            // Try to save the user
            if ($users->save($user)) {
                // Get the created user and assign default 'user' role
                $createdUser = $this->userModel->where('username', $username)->first();
                
                if ($createdUser) {
                    // Get the 'user' role
                    $roleModel = new \App\Models\Role();
                    $userRole = $roleModel->where('name', 'user')->first();
                    
                    if ($userRole) {
                        // Update user with role_id
                        $this->userModel->update($createdUser['id'], [
                            'active' => true
                        ]);

                        // To get the complete user object with ID, we need to get from the database
                        $user = $users->findById($createdUser['id']);

                        // Add to default group
                        $users->addToDefaultGroup($user);

                    }
                }
                
                session()->setFlashdata('success', 'Registrasi berhasil, silakan login');
                return redirect()->to('/auth/login');
            } else {
                // Get validation errors
                $errors = $users->errors();
                $errorMessage = 'Registrasi gagal';
                
                if (!empty($errors)) {
                    $errorMessage = implode(', ', $errors);
                }
                
                session()->setFlashdata('error', $errorMessage);
            }
        }

        return $this->render('auth/register');
    }

    public function logout()
    {
        session()->destroy();
        auth()->logout();
        return redirect()->to('/auth/login');
    }
}
