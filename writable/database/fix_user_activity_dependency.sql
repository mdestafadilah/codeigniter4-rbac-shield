-- ===================================================================
-- Script untuk memperbaiki dependency user_apps_activity
-- dari legacy_users ke users (Shield)
-- ===================================================================

-- STEP 1: Verifikasi data - cek apakah ada id_user yang tidak ada di tabel users
-- Jalankan query ini terlebih dahulu untuk memastikan data integrity
SELECT 'Checking for orphaned records...' AS status;

SELECT 
    COUNT(*) as orphaned_count,
    'Records in user_apps_activity with id_user not in users table' as description
FROM user_apps_activity ua
LEFT JOIN users u ON ua.id_user = u.id
WHERE u.id IS NULL;

-- Jika ada orphaned records, tampilkan detailnya
SELECT DISTINCT 
    ua.id_user,
    COUNT(*) as activity_count
FROM user_apps_activity ua
LEFT JOIN users u ON ua.id_user = u.id
WHERE u.id IS NULL
GROUP BY ua.id_user
ORDER BY ua.id_user;

-- ===================================================================
-- STEP 2: (OPTIONAL) Update orphaned records
-- Jika ada data dengan id_user yang tidak ada di users,
-- Anda bisa:
-- Option A: Set ke default user (9999 atau admin user)
-- Option B: Delete orphaned records
-- Uncomment salah satu pilihan di bawah jika diperlukan:
-- ===================================================================

-- Option A: Update ke default user (9999)
-- UPDATE user_apps_activity 
-- SET id_user = 9999
-- WHERE id_user NOT IN (SELECT id FROM users);

-- Option B: Delete orphaned records (HATI-HATI!)
-- DELETE FROM user_apps_activity 
-- WHERE id_user NOT IN (SELECT id FROM users);

-- ===================================================================
-- STEP 3: Drop old foreign key constraint
-- ===================================================================
SELECT 'Dropping old foreign key constraint...' AS status;

ALTER TABLE user_apps_activity 
DROP CONSTRAINT IF EXISTS user_apps_activity_id_user_foreign;

-- ===================================================================
-- STEP 4: Add new foreign key pointing to Shield users table
-- ===================================================================
SELECT 'Adding new foreign key constraint to users table...' AS status;

ALTER TABLE user_apps_activity 
ADD CONSTRAINT user_apps_activity_id_user_foreign 
FOREIGN KEY (id_user) 
REFERENCES users(id) 
ON UPDATE CASCADE 
ON DELETE CASCADE;

-- ===================================================================
-- STEP 5: Verifikasi constraint baru
-- ===================================================================
SELECT 'Verifying new constraint...' AS status;

SELECT 
    conname as constraint_name,
    contype as constraint_type,
    pg_get_constraintdef(oid) as constraint_definition
FROM pg_constraint 
WHERE conname = 'user_apps_activity_id_user_foreign';

SELECT 'Migration completed successfully!' AS status;
