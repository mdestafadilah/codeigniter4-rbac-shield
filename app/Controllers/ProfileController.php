<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->getUserWithRole($userId);

        if (!$user) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'My Profile',
            'user' => $user
        ];

        return view('profile/index', $data);
    }

    public function edit()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Edit Profile',
            'user' => $user
        ];

        return view('profile/edit', $data);
    }

    public function update()
    {
        $userId = session()->get('user_id');
        
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,' . $userId . ']',
            'email' => 'required|valid_email|is_unique[users.email,id,' . $userId . ']',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->userModel->update($userId, $data)) {
            // Update session data
            session()->set([
                'username' => $data['username'],
                'email' => $data['email']
            ]);

            return redirect()->to('/profile')->with('success', 'Profile updated successfully!');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update profile. Please try again.');
    }

    public function password()
    {
        $data = [
            'title' => 'Change Password'
        ];

        return view('profile/password', $data);
    }

    public function updatePassword()
    {
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return redirect()->to('/login');
        }

        // Verify current password
        if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        // Update password
        $data = [
            'password' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->userModel->update($userId, $data)) {
            return redirect()->to('/profile')->with('success', 'Password changed successfully!');
        }

        return redirect()->back()->with('error', 'Failed to change password. Please try again.');
    }
}