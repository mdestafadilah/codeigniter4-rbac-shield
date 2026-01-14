<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Run seeders in the correct order
        // 1. First create permissions
        $this->call('PermissionSeeder');
        
        // 2. Then create roles and assign permissions
        $this->call('RoleSeeder');
        
        // 3. Finally create users and assign roles
        $this->call('UserSeeder');
    }
}
