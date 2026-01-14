<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CleanupLegacy extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:cleanup-legacy';
    protected $description = 'Remove legacy_users and legacy_settings tables';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        CLI::write('=== Cleanup Legacy Tables ===', 'yellow');
        CLI::newLine();

        // Step 1: Verify that foreign key has been updated
        CLI::write('Step 1: Verifying foreign key constraint...', 'blue');
        $query = "
            SELECT pg_get_constraintdef(oid) as constraint_definition
            FROM pg_constraint 
            WHERE conname = 'user_apps_activity_id_user_foreign'
        ";
        
        $result = $db->query($query);
        $constraint = $result->getRow();

        if ($constraint && strpos($constraint->constraint_definition, 'REFERENCES users(id)') !== false) {
            CLI::write('✓ Foreign key correctly references users table', 'green');
        } else {
            CLI::error('✗ ERROR: Foreign key still references legacy_users or not found!');
            CLI::write('Please run: php spark db:fix-activity-constraint first', 'yellow');
            return;
        }
        CLI::newLine();

        // Step 2: Check if legacy tables exist
        CLI::write('Step 2: Checking legacy tables...', 'blue');
        $legacyUsers = $db->tableExists('legacy_users');
        $legacySettings = $db->tableExists('legacy_settings');

        if (!$legacyUsers && !$legacySettings) {
            CLI::write('✓ No legacy tables found. Already cleaned up.', 'green');
            return;
        }

        if ($legacyUsers) {
            CLI::write('  - legacy_users found');
        }
        if ($legacySettings) {
            CLI::write('  - legacy_settings found');
        }
        CLI::newLine();

        // Step 3: Confirm deletion
        CLI::write('⚠  WARNING: This will permanently delete legacy tables!', 'red');
        CLI::newLine();
        
        $confirm = CLI::prompt('Are you sure you want to continue?', ['y', 'n']);
        
        if ($confirm !== 'y') {
            CLI::write('Cleanup cancelled.', 'yellow');
            return;
        }
        CLI::newLine();

        try {
            // Step 4: Drop legacy_users
            if ($legacyUsers) {
                CLI::write('Step 3: Dropping legacy_users table...', 'blue');
                $db->query('DROP TABLE IF EXISTS legacy_users CASCADE');
                CLI::write('✓ legacy_users dropped successfully', 'green');
                CLI::newLine();
            }

            // Step 5: Drop legacy_settings
            if ($legacySettings) {
                CLI::write('Step 4: Dropping legacy_settings table...', 'blue');
                $db->query('DROP TABLE IF EXISTS legacy_settings CASCADE');
                CLI::write('✓ legacy_settings dropped successfully', 'green');
                CLI::newLine();
            }

            // Step 6: Verify cleanup
            CLI::write('Step 5: Verifying cleanup...', 'blue');
            $query = "
                SELECT tablename 
                FROM pg_tables 
                WHERE schemaname = 'public' 
                AND tablename IN ('legacy_users', 'legacy_settings')
            ";
            
            $result = $db->query($query);
            $remainingTables = $result->getResultArray();

            if (empty($remainingTables)) {
                CLI::write('✓ All legacy tables removed successfully', 'green');
            } else {
                CLI::write('✗ Some legacy tables still exist:', 'red');
                foreach ($remainingTables as $table) {
                    CLI::write("  - {$table['tablename']}");
                }
            }
            CLI::newLine();

            // Show current tables
            CLI::write('=== Current Database Tables ===', 'yellow');
            CLI::newLine();
            
            $query = "
                SELECT tablename
                FROM pg_tables 
                WHERE schemaname = 'public'
                ORDER BY tablename
            ";
            
            $result = $db->query($query);
            $tables = $result->getResultArray();

            foreach ($tables as $table) {
                $category = 'Other';
                $name = $table['tablename'];
                
                if (strpos($name, 'auth_') === 0) {
                    $category = '[Shield Auth]';
                } elseif ($name === 'users') {
                    $category = '[Shield Users]';
                } elseif (in_array($name, ['roles', 'permissions', 'role_permissions'])) {
                    $category = '[Custom RBAC]';
                } elseif (in_array($name, ['mahasiswa', 'menus', 'user_apps_activity'])) {
                    $category = '[Application]';
                } elseif (in_array($name, ['migrations', 'settings'])) {
                    $category = '[Framework]';
                }
                
                CLI::write("  {$category} {$name}");
            }
            CLI::newLine();

            CLI::write('=== Cleanup Completed Successfully! ===', 'green');
            CLI::newLine();

        } catch (\Exception $e) {
            CLI::error('ERROR: ' . $e->getMessage());
            CLI::newLine();
            CLI::write('Cleanup failed. Some changes may have been made.', 'red');
        }
    }
}
