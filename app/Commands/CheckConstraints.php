<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckConstraints extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:check-constraints';
    protected $description = 'Check current database constraints and legacy tables status';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        CLI::write('=== Checking Foreign Key Constraints ===', 'yellow');
        CLI::newLine();

        // Check current constraint on user_apps_activity
        $query = "
            SELECT 
                conname as constraint_name, 
                pg_get_constraintdef(oid) as constraint_definition
            FROM pg_constraint 
            WHERE conname = 'user_apps_activity_id_user_foreign'
        ";

        $result = $db->query($query);
        $constraints = $result->getResultArray();

        if (empty($constraints)) {
            CLI::write('✓ No foreign key constraint found (or already been dropped)', 'green');
        } else {
            foreach ($constraints as $constraint) {
                CLI::write('Current constraint:', 'blue');
                CLI::write("  Name: {$constraint['constraint_name']}");
                CLI::write("  Definition: {$constraint['constraint_definition']}");
            }
        }
        CLI::newLine();

        // Check if legacy tables exist
        CLI::write('=== Checking Legacy Tables ===', 'yellow');
        CLI::newLine();
        
        $query = "
            SELECT tablename 
            FROM pg_tables 
            WHERE schemaname = 'public' 
            AND tablename IN ('legacy_users', 'legacy_settings')
        ";

        $result = $db->query($query);
        $legacyTables = $result->getResultArray();

        if (empty($legacyTables)) {
            CLI::write('✓ No legacy tables found', 'green');
        } else {
            CLI::write('Legacy tables still exist:', 'red');
            foreach ($legacyTables as $table) {
                CLI::write("  - {$table['tablename']}");
            }
        }
        CLI::newLine();

        // Check if users table exists (Shield)
        CLI::write('=== Checking Shield Tables ===', 'yellow');
        CLI::newLine();
        
        if ($db->tableExists('users')) {
            CLI::write('✓ Shield users table exists', 'green');
        } else {
            CLI::write('✗ ERROR: users table not found! Shield migration might not be complete.', 'red');
            return;
        }
        CLI::newLine();

        // Count records in user_apps_activity
        $query = "SELECT COUNT(*) as count FROM user_apps_activity";
        $result = $db->query($query);
        $count = $result->getRow()->count;

        CLI::write('=== User Activity Data ===', 'yellow');
        CLI::newLine();
        CLI::write("Total records in user_apps_activity: {$count}");
        CLI::newLine();

        if ($count > 0) {
            // Check for orphaned records
            CLI::write('Checking for orphaned records...', 'blue');
            $query = "
                SELECT COUNT(*) as orphaned_count
                FROM user_apps_activity ua
                LEFT JOIN users u ON ua.id_user = u.id
                WHERE u.id IS NULL
            ";
            
            $result = $db->query($query);
            $orphanedCount = $result->getRow()->orphaned_count;
            
            if ($orphanedCount > 0) {
                CLI::write("⚠ WARNING: Found {$orphanedCount} orphaned records (id_user not in users table)", 'red');
            } else {
                CLI::write('✓ No orphaned records found', 'green');
            }
            CLI::newLine();
        }

        CLI::write('=== Summary ===', 'yellow');
        CLI::newLine();
        CLI::write('Ready to proceed with migration.', 'green');
    }
}
