<?php

namespace App\Controllers\Auth;

use CodeIgniter\Shield\Controllers\RegisterController as ShieldRegister;
use CodeIgniter\HTTP\RedirectResponse;

class RegisterController extends ShieldRegister
{
    public function registerAction(): RedirectResponse
    {
        $request = service('request');
        
        // 1. Get Rules
        $rules = $this->getValidationRules();

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Get Data
        // Setup manual data to ensure everything is captured
        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role_id'  => null, // Let Model handle default
            'active'   => 1,
            'status'   => 'active'
        ];
        
        // 3. Create User Entity
        $users = $this->getUserProvider();
        $user = new \CodeIgniter\Shield\Entities\User($data);

        // 4. Save User
        try {
            if (! $users->save($user)) {
                return redirect()->back()->withInput()->with('errors', $users->errors());
            }

            // 5. Get Inserted User
            $user = $this->getUserProvider()->findById($this->getUserProvider()->getInsertID());

            // 6. Create Identity
            $user->createEmailIdentity([
                'email'    => $request->getPost('email'),
                'password' => $request->getPost('password'),
            ]);

            // 7. Add to Default Group
            $user->addGroup(config('Auth')->defaultGroup ?? 'user');

            // 8. Success Redirect
            return redirect()->to(config('Auth')->registerRedirect())
                ->with('message', lang('Auth.registerSuccess'));

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
