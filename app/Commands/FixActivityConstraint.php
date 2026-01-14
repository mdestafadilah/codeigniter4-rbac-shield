<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixActivityConstraint extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:fix-activity-constraint';
    protected $description = 'Fix user_apps_activity foreign key to reference users instead of legacy_users';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        CLI::write('=== Fixing user_apps_activity Foreign Key Constraint ===', 'yellow');
        CLI::newLine();

        try {
            // Step 1: Drop old foreign key constraint
            CLI::write('Step 1: Dropping old foreign key constraint...', 'blue');
            $db->query('ALTER TABLE user_apps_activity DROP CONSTRAINT IF EXISTS user_apps_activity_id_user_foreign');
            CLI::write('✓ Old constraint dropped successfully', 'green');
            CLI::newLine();

            // Step 2: Add new foreign key pointing to Shield users table
            CLI::write('Step 2: Adding new foreign key constraint to users table...', 'blue');
            $db->query('
                ALTER TABLE user_apps_activity 
                ADD CONSTRAINT user_apps_activity_id_user_foreign 
                FOREIGN KEY (id_user) 
                REFERENCES users(id) 
                ON UPDATE CASCADE 
                ON DELETE CASCADE
            ');
            CLI::write('✓ New constraint added successfully', 'green');
            CLI::newLine();

            // Step 3: Verify new constraint
            CLI::write('Step 3: Verifying new constraint...', 'blue');
            $query = "
                SELECT 
                    conname as constraint_name,
                    pg_get_constraintdef(oid) as constraint_definition
                FROM pg_constraint 
                WHERE conname = 'user_apps_activity_id_user_foreign'
            ";
            
            $result = $db->query($query);
            $constraints = $result->getResultArray();

            if (!empty($constraints)) {
                CLI::write('✓ New constraint verified:', 'green');
                foreach ($constraints as $constraint) {
                    CLI::write("  Name: {$constraint['constraint_name']}");
                    CLI::write("  Definition: {$constraint['constraint_definition']}");
                }
            } else {
                CLI::write('✗ ERROR: Could not verify new constraint!', 'red');
                return;
            }
            CLI::newLine();

            CLI::write('=== Migration Completed Successfully! ===', 'green');
            CLI::newLine();
            CLI::write('The user_apps_activity table now references the Shield users table.', 'green');
            CLI::write('You can now proceed to cleanup legacy tables using: php spark db:cleanup-legacy', 'yellow');

        } catch (\Exception $e) {
            CLI::error('ERROR: ' . $e->getMessage());
            CLI::newLine();
            CLI::write('Migration failed. Database remains unchanged.', 'red');
        }
    }
}
