<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class StartShield extends BaseCommand
{
    protected $group = 'Fix';
    protected $name = 'fix:start_shield';
    protected $description = 'Migrates data from legacy_users to Shield tables.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        // 1. Get Legacy Users
        $query = $db->table('legacy_users')->get();
        $legacyUsers = $query->getResultArray();
        
        $usersModel = new UserModel();
        
        foreach ($legacyUsers as $oldUser) {
            CLI::write("Migrating user: " . $oldUser['username'], 'yellow');
            
            // Create Shield User
            // Shield UserModel handles auth_identities creation if we pass credentials? 
            // Better to use Entity.
            
            $user = new User([
                'username' => $oldUser['username'],
                'email'    => $oldUser['email'],
                'password' => 'temp123', // Dummy, we will overwrite hash
                'active'   => $oldUser['active'] ?? 1,
            ]);
            
            // We need to bypass validation or standard create if we want to preserve hash?
            // Standard Shield 'create' hashes the password. 
            // If we want to keep old hash (assuming it matches Shield's algo), we might need direct insert for Identity.
            
            // Strategy: Create user, then update Identity with old hash.
            
            try {
                $usersModel->save($user);
                $newUserId = $usersModel->getInsertID(); // OR $user->id if populated
                
                if (!$newUserId) {
                    $newUserId = $usersModel->getInsertID();
                }

                if ($newUserId) {
                     // Update Password Hash directly in auth_identities
                     // Shield usually creates an identity automatically? No, only if using Validatable traits etc.
                     // If using $usersModel->save($user), it might NOT create identity unless password is in the data and Entity handles it.
                     
                     // Let's create Identity manually to be safe and use OLD hash
                     $identityData = [
                         'user_id' => $newUserId,
                         'type'    => 'email_password',
                         'secret'  => $oldUser['password'], // Old Hash
                         'secret2' => $oldUser['email'],
                         'created_at' => date('Y-m-d H:i:s'),
                         'updated_at' => date('Y-m-d H:i:s'),
                     ];
                     
                     // Check if identity exists (Shield might auto-create if we passed password)
                     $identityExists = $db->table('auth_identities')->where('user_id', $newUserId)->where('type', 'email_password')->countAllResults();
                     
                     if ($identityExists) {
                         $db->table('auth_identities')
                            ->where('user_id', $newUserId)
                            ->where('type', 'email_password')
                            ->update(['secret' => $oldUser['password']]); // Overwrite with old hash
                     } else {
                         $db->table('auth_identities')->insert($identityData);
                     }
                     
                     // 3. Map Roles
                     $role = strtolower($oldUser['role'] ?? 'user');
                     // Simple mapping
                     if ($role === 'superadmin' || $role === 'admin' || $role === 'developer' || $role === 'beta') {
                         // use role as group
                     } else {
                         $role = 'user';
                     }
                     
                     $groupData = [
                         'user_id' => $newUserId,
                         'group' => $role,
                         'created_at' => date('Y-m-d H:i:s')
                     ];
                     $db->table('auth_groups_users')->insert($groupData);
                     
                     CLI::write("User migrated successfully. ID: {$newUserId}, Role: {$role}", 'green');
                } else {
                    CLI::write("Failed to save user (No ID returned).", 'red');
                    // Check errors
                     foreach ($usersModel->errors() as $error) {
                        CLI::write($error, 'red');
                    }
                }

            } catch (\Exception $e) {
                CLI::write("Error migrating user: " . $e->getMessage(), 'red');
            }
        }
    }
}
