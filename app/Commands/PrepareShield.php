<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class PrepareShield extends BaseCommand
{
    protected $group = 'Fix';
    protected $name = 'fix:prepare_shield';
    protected $description = 'Renames existing tables to legacy_ prefixes to allow Shield migration.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();

        // 1. Handle Users Table
        if ($db->tableExists('users')) {
            // Drop FK if exists
            try {
                 $db->query('ALTER TABLE "users" DROP CONSTRAINT IF EXISTS "users_role_id_foreign"');
                 CLI::write("Dropped users_role_id_foreign constraint.", 'green');
            } catch (\Exception $e) {
                CLI::write("Constraint removal skipped or failed: " . $e->getMessage(), 'yellow');
            }

            // Rename users -> legacy_users
            if (!$db->tableExists('legacy_users')) {
                $forge->renameTable('users', 'legacy_users');
                CLI::write("Renamed 'users' to 'legacy_users'.", 'green');
                
                // Rename PK constraint
                try {
                    $db->query('ALTER TABLE "legacy_users" RENAME CONSTRAINT "pk_users" TO "pk_legacy_users"');
                    CLI::write("Renamed constraint 'pk_users' to 'pk_legacy_users'.", 'green');
                } catch (\Exception $e) {
                     CLI::write("PK Rename failed (or already done): " . $e->getMessage(), 'yellow');
                }
                
                // Rename Unique Keys
                try {
                    $db->query('ALTER TABLE "legacy_users" RENAME CONSTRAINT "users_username_key" TO "legacy_users_username_key"');
                    CLI::write("Renamed constraint 'users_username_key'.", 'green');
                } catch (\Exception $e) {
                    CLI::write("Username Key Rename failed: " . $e->getMessage(), 'yellow');
                }

                try {
                    $db->query('ALTER TABLE "legacy_users" RENAME CONSTRAINT "users_email_key" TO "legacy_users_email_key"');
                    CLI::write("Renamed constraint 'users_email_key'.", 'green');
                } catch (\Exception $e) {
                    CLI::write("Email Key Rename failed: " . $e->getMessage(), 'yellow');
                }
            } else {
                CLI::write("'legacy_users' already exists. Skipping table rename.", 'yellow');
                
                // Retry Constraint Renames if they exist
                 try {
                    $db->query('ALTER TABLE "legacy_users" RENAME CONSTRAINT "pk_users" TO "pk_legacy_users"');
                    CLI::write("Renamed constraint 'pk_users' to 'pk_legacy_users'.", 'green');
                } catch (\Exception $e) {
                     CLI::write("PK Rename failed (or already done): " . $e->getMessage(), 'yellow');
                }

                try {
                    $db->query('ALTER TABLE "legacy_users" RENAME CONSTRAINT "users_username_key" TO "legacy_users_username_key"');
                    CLI::write("Renamed constraint 'users_username_key'.", 'green');
                } catch (\Exception $e) {
                    CLI::write("Username Key Rename failed: " . $e->getMessage(), 'yellow');
                }

                try {
                    $db->query('ALTER TABLE "legacy_users" RENAME CONSTRAINT "users_email_key" TO "legacy_users_email_key"');
                    CLI::write("Renamed constraint 'users_email_key'.", 'green');
                } catch (\Exception $e) {
                    CLI::write("Email Key Rename failed: " . $e->getMessage(), 'yellow');
                }
            }

        }

        // 2. Handle Settings Table
        if ($db->tableExists('settings')) {
             if (!$db->tableExists('legacy_settings')) {
                $forge->renameTable('settings', 'legacy_settings');
                CLI::write("Renamed 'settings' to 'legacy_settings'.", 'green');
            } else {
                CLI::write("'legacy_settings' already exists. Skipping rename.", 'yellow');
            }
        }
    }
}
