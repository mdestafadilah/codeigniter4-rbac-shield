-- ===================================================================
-- Script untuk menghapus tabel-tabel legacy yang tidak digunakan
-- PENTING: Jalankan script ini HANYA SETELAH fix_user_activity_dependency.sql
-- sudah berhasil dijalankan!
-- ===================================================================

-- BACKUP REMINDER
SELECT '⚠️  WARNING: BACKUP DATABASE FIRST!' AS reminder;
SELECT 'This script will permanently delete legacy tables.' AS reminder;
SELECT 'Make sure fix_user_activity_dependency.sql has been executed successfully!' AS reminder;

-- ===================================================================
-- STEP 1: Verifikasi bahwa foreign key sudah diperbaiki
-- ===================================================================
SELECT 'Verifying that foreign key has been updated...' AS status;

SELECT 
    conname as constraint_name,
    pg_get_constraintdef(oid) as constraint_definition
FROM pg_constraint 
WHERE conname = 'user_apps_activity_id_user_foreign';

-- Hasil harus menunjukkan REFERENCES users(id), BUKAN legacy_users(id)

-- ===================================================================
-- STEP 2: (OPTIONAL) Backup legacy_users data sebelum dihapus
-- Uncomment jika ingin backup terlebih dahulu
-- ===================================================================

-- CREATE TABLE legacy_users_backup AS 
-- SELECT * FROM legacy_users;

-- CREATE TABLE legacy_settings_backup AS 
-- SELECT * FROM legacy_settings;

-- ===================================================================
-- STEP 3: Drop legacy_users table
-- ===================================================================
SELECT 'Dropping legacy_users table...' AS status;

DROP TABLE IF EXISTS legacy_users CASCADE;

-- ===================================================================
-- STEP 4: Drop legacy_settings table
-- ===================================================================
SELECT 'Dropping legacy_settings table...' AS status;

DROP TABLE IF EXISTS legacy_settings CASCADE;

-- ===================================================================
-- STEP 5: Verifikasi tabel sudah terhapus
-- ===================================================================
SELECT 'Verifying tables have been dropped...' AS status;

SELECT 
    tablename,
    'Still exists - should be empty list' as note
FROM pg_tables 
WHERE schemaname = 'public' 
AND tablename IN ('legacy_users', 'legacy_settings');

SELECT 'Cleanup completed successfully!' AS status;

-- ===================================================================
-- STEP 6: Daftar tabel yang masih ada (untuk verifikasi)
-- ===================================================================
SELECT 'Current database tables:' AS status;

SELECT 
    tablename,
    CASE 
        WHEN tablename LIKE 'auth_%' THEN 'Shield Auth'
        WHEN tablename IN ('users') THEN 'Shield Users'
        WHEN tablename IN ('roles', 'permissions', 'role_permissions') THEN 'Custom RBAC'
        WHEN tablename IN ('mahasiswa', 'menus', 'user_apps_activity') THEN 'Application'
        WHEN tablename IN ('migrations', 'settings') THEN 'Framework'
        ELSE 'Other'
    END as category
FROM pg_tables 
WHERE schemaname = 'public'
ORDER BY category, tablename;
