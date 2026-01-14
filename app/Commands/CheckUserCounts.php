<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckUserCounts extends BaseCommand
{
    protected $group = 'Fix';
    protected $name = 'check:counts';
    protected $description = 'Checks counts of users and identities.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        $usersCount = $db->table('users')->countAllResults();
        
        $identitiesCount = 0;
        if ($db->tableExists('auth_identities')) {
             $identitiesCount = $db->table('auth_identities')->countAllResults();
        } else {
            CLI::write("Table 'auth_identities' does not exist!", 'red');
        }

        CLI::write("Users count: {$usersCount}", 'green');
        CLI::write("Identities count: {$identitiesCount}", 'green');
        
        // Show one user to see columns
        $user = $db->table('users')->get()->getFirstRow();
        if ($user) {
             CLI::write("User sample columns: " . implode(', ', array_keys((array)$user)));
        }
    }
}
