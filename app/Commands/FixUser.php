<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixUser extends BaseCommand
{
    protected $group       = 'Auth';
    protected $name        = 'auth:fixuser';
    protected $description = 'Fixes a user account (activates and resets password).';
    protected $usage       = 'auth:fixuser [email] [password]';
    protected $arguments   = [
        'email'    => 'The email address of the user to fix',
        'password' => 'The new password to set',
    ];

    public function run(array $params)
    {
        $email = array_shift($params);
        $password = array_shift($params);

        if (empty($email) || empty($password)) {
            CLI::error('Usage: auth:fixuser [email] [password]');
            return;
        }

        $userModel = model('App\Models\UserModel');
        
        // Find User by email from Users table (since we added it)
        $user = $userModel->where('email', $email)->first();

        // If not found in users table, try identity?
        if (!$user) {
             // Try standard Shield identity lookup
             $identityModel = model('CodeIgniter\Shield\Models\UserIdentityModel');
             $identity = $identityModel->where('secret', $email)->first();
             if ($identity) {
                 $user = $userModel->findById($identity->user_id);
             }
        }

        if (!$user) {
            CLI::error("User not found: $email");
            return;
        }

        CLI::write("Found User ID: " . $user->id, 'green');

        // 1. Set Active
        $userModel->update($user->id, ['active' => 1, 'status' => 'active']);
        CLI::write("Updated active status to 1", 'green');

        // 2. Reset Password
        // Check if identity exists
        $identity = $user->getEmailIdentity();
        
        $identityModel = model('CodeIgniter\Shield\Models\UserIdentityModel');

        if ($identity) {
            // Update existing identity
            $identityModel->update($identity->id, [
                'secret2' => password_hash($password, PASSWORD_DEFAULT)
            ]);
            CLI::write("Updated existing password identity.", 'green');
        } else {
            // Create new identity
            $user->createEmailIdentity([
                'email'    => $email,
                'password' => $password,
            ]);
            CLI::write("Created NEW email identity.", 'green');
        }

        CLI::write("Done! You can now login with: $email / $password", 'yellow');
    }
}
