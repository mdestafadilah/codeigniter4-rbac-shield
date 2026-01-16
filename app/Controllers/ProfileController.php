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
        $userId = auth()->id();
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
        // Use Shield's user entity
        $user = auth()->user();

        if (!$user) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Edit Profile',
            'user' => $user->toArray() // Fix: Convert entity to array for view
        ];

        return view('profile/edit', $data);
    }

    public function update()
    {
        $user = auth()->user();
        $userId = $user->id;
        
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,' . $userId . ']',
            'email' => 'required|valid_email|is_unique[users.email,id,' . $userId . ']',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data for update
        $user->username = $this->request->getPost('username');
        $user->email    = $this->request->getPost('email');

        // Use Shield's method to save the user (handles events etc)
        $users = auth()->getProvider();
        if ($users->save($user)) {
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

        $user = auth()->user();
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        // Verify current password using Shield's checker
        $result = auth()->check([
            'email'    => $user->email,
            'password' => $currentPassword,
        ]);

        if (!$result->isOK()) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        // Update password
        $user->password = $newPassword; // Shield Entity handles hashing
        $users = auth()->getProvider();
        
        if ($users->save($user)) {
             return redirect()->to('/profile')->with('success', 'Password changed successfully!');
        }

        return redirect()->back()->with('error', 'Failed to change password. Please try again.');
    }
}