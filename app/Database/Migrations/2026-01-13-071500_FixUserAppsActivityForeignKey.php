<?php

/**
 * Migration: Fix User Apps Activity Foreign Key
 * 
 * This migration documents the manual fix performed on 2026-01-13
 * to update the foreign key constraint in user_apps_activity table
 * from referencing legacy_users to Shield's users table.
 * 
 * This migration is for documentation purposes only.
 * The actual changes were performed using: php spark db:fix-activity-constraint
 * 
 * Changes made:
 * - Dropped: user_apps_activity_id_user_foreign (referenced legacy_users)
 * - Created: user_apps_activity_id_user_foreign (references users)
 * 
 * Related:
 * - Tabel legacy_users and legacy_settings were dropped after this fix
 * - See: app/Commands/FixActivityConstraint.php
 * - See: app/Commands/CleanupLegacy.php
 */

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixUserAppsActivityForeignKey extends Migration
{
    public function up()
    {
        // This is a documentation migration
        // The actual changes were performed manually via Spark command
        // 
        // If running fresh migrations, the LogActivity migration
        // already creates the correct foreign key to users table
        
        // Check if table exists and has the old constraint
        if (!$this->db->tableExists('user_apps_activity')) {
            // Table doesn't exist yet, skip
            return;
        }
        
        // Check if constraint exists and points to wrong table
        $query = "
            SELECT pg_get_constraintdef(oid) as def
            FROM pg_constraint 
            WHERE conname = 'user_apps_activity_id_user_foreign'
        ";
        
        $result = $this->db->query($query)->getRow();
        
        // If constraint exists and references legacy_users, fix it
        if ($result && strpos($result->def, 'legacy_users') !== false) {
            // Drop old constraint
            $this->db->query('
                ALTER TABLE user_apps_activity 
                DROP CONSTRAINT IF EXISTS user_apps_activity_id_user_foreign
            ');
            
            // Add new constraint pointing to Shield users table
            $this->db->query('
                ALTER TABLE user_apps_activity 
                ADD CONSTRAINT user_apps_activity_id_user_foreign 
                FOREIGN KEY (id_user) 
                REFERENCES users(id) 
                ON UPDATE CASCADE 
                ON DELETE CASCADE
            ');
        }
    }

    public function down()
    {
        // Cannot rollback as legacy_users table no longer exists
        // This migration is one-way only
    }
}
