<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ForceRename extends BaseCommand
{
    protected $group = 'Fix';
    protected $name = 'fix:force_rename';
    protected $description = 'Force renames constraints using raw SQL.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        $queries = [
            'PK' => 'ALTER TABLE "legacy_users" RENAME CONSTRAINT "pk_users" TO "pk_legacy_users"',
            'Username Key' => 'ALTER TABLE "legacy_users" RENAME CONSTRAINT "users_username_key" TO "legacy_users_username_key"',
            'Email Key' => 'ALTER TABLE "legacy_users" RENAME CONSTRAINT "users_email_key" TO "legacy_users_email_key"',
            'PK Settings' => 'ALTER TABLE "legacy_settings" RENAME CONSTRAINT "pk_settings" TO "pk_legacy_settings"'
        ];

        foreach ($queries as $label => $sql) {
            try {
                CLI::write("Attempting to rename {$label}...", 'yellow');
                $db->query($sql);
                CLI::write("Success: {$label}", 'green');
            } catch (\Throwable $e) {
                CLI::write("Failed {$label}: " . $e->getMessage(), 'red');
            }
        }
    }
}
