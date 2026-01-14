<?php

require 'vendor/autoload.php';

// Connect to database
$db = \Config\Database::connect();

echo "=== Checking Foreign Key Constraints ===\n\n";

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
    echo "✓ No foreign key constraint found (or already been dropped)\n\n";
} else {
    foreach ($constraints as $constraint) {
        echo "Current constraint:\n";
        echo "  Name: {$constraint['constraint_name']}\n";
        echo "  Definition: {$constraint['constraint_definition']}\n\n";
    }
}

// Check if legacy_users table exists
echo "=== Checking Legacy Tables ===\n\n";
$query = "
    SELECT tablename 
    FROM pg_tables 
    WHERE schemaname = 'public' 
    AND tablename IN ('legacy_users', 'legacy_settings')
";

$result = $db->query($query);
$legacyTables = $result->getResultArray();

if (empty($legacyTables)) {
    echo "✓ No legacy tables found\n\n";
} else {
    echo "Legacy tables still exist:\n";
    foreach ($legacyTables as $table) {
        echo "  - {$table['tablename']}\n";
    }
    echo "\n";
}

// Check if users table exists (Shield)
echo "=== Checking Shield Tables ===\n\n";
$query = "
    SELECT tablename 
    FROM pg_tables 
    WHERE schemaname = 'public' 
    AND tablename = 'users'
";

$result = $db->query($query);
$usersTables = $result->getResultArray();

if (empty($usersTables)) {
    echo "✗ ERROR: 'users' table not found! Shield migration might not be complete.\n\n";
} else {
    echo "✓ Shield 'users' table exists\n\n";
}

// Count records in user_apps_activity
$query = "SELECT COUNT(*) as count FROM user_apps_activity";
$result = $db->query($query);
$count = $result->getRow()->count;

echo "=== User Activity Data ===\n\n";
echo "Total records in user_apps_activity: {$count}\n\n";

if ($count > 0) {
    // Check for orphaned records
    echo "Checking for orphaned records...\n";
    $query = "
        SELECT COUNT(*) as orphaned_count
        FROM user_apps_activity ua
        LEFT JOIN users u ON ua.id_user = u.id
        WHERE u.id IS NULL
    ";
    
    $result = $db->query($query);
    $orphanedCount = $result->getRow()->orphaned_count;
    
    if ($orphanedCount > 0) {
        echo "⚠ WARNING: Found {$orphanedCount} orphaned records (id_user not in users table)\n\n";
    } else {
        echo "✓ No orphaned records found\n\n";
    }
}

echo "=== Summary ===\n\n";
echo "Ready to proceed with migration.\n";
