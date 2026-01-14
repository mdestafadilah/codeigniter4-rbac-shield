# Migration Files Summary - Post Shield Integration

## Overview

Setelah integrasi Shield dan cleanup database, migration files telah diperbarui untuk mencerminkan struktur database terbaru.

---

## Active Migrations

### Application Migrations (7 files)

1. **2025-08-05-050158_CreateMahasiswaTable.php**

   - Status: ✅ Active
   - Purpose: Student data table
   - No changes needed

2. **2025-08-05-053126_CreateRolesTable.php**

   - Status: ✅ Active
   - Purpose: Custom RBAC roles
   - No changes needed

3. **2025-08-05-053129_CreatePermissionsTable.php**

   - Status: ✅ Active
   - Purpose: Custom RBAC permissions
   - No changes needed

4. **2025-08-05-053132_CreateRolePermissionsTable.php**

   - Status: ✅ Active
   - Purpose: RBAC role-permission mapping
   - No changes needed

5. **2025-10-17-071408_CreateSettingsTable.php**

   - Status: ✅ Active
   - Purpose: Application settings
   - No changes needed

6. **2025-11-19-204424_LogActivity.php**

   - Status: ✅ Active (Already Correct!)
   - Purpose: User activity logging
   - **Foreign Key**: References `users` table (Shield) ✅
   - Line 82: `$this->forge->addForeignKey('id_user', 'users', 'id', 'CASCADE', 'CASCADE');`

7. **2025-12-10-022338_CreateMenusTable.php**

   - Status: ✅ Active
   - Purpose: Menu management
   - No changes needed

8. **2026-01-13-071500_FixUserAppsActivityForeignKey.php**
   - Status: ✅ New (Batch 4)
   - Purpose: Documentation of manual foreign key fix
   - This migration is idempotent and safe to run multiple times

---

## Archived Migrations

Location: [`app/Database/Migrations/archived/`](file:///D:/DEV/PHP/codeigniter4_RBAC_boilerplate/app/Database/Migrations/archived/)

1. **2025-08-05-050154_CreateUsersTable.php.legacy**

   - Reason: Replaced by Shield's create_auth_tables
   - This created the old custom users table (later renamed to legacy_users)

2. **2025-08-05-053136_UpdateUsersTableForRBAC.php.legacy**
   - Reason: No longer needed after Shield integration
   - Shield handles authentication separately from RBAC

**Documentation**: See [`archived/README.md`](file:///D:/DEV/PHP/codeigniter4_RBAC_boilerplate/app/Database/Migrations/archived/README.md)

---

## Shield Migrations (Vendor)

From: `vendor/codeigniter4/shield/src/Database/Migrations/`

1. **2020-12-28-223112_create_auth_tables** (Batch 2)
   - Creates Shield authentication tables:
     - `users`
     - `auth_identities`
     - `auth_groups_users`
     - `auth_permissions_users`
     - `auth_logins`
     - `auth_token_logins`
     - `auth_remember_tokens`

---

## Settings Migrations (Vendor)

From: `vendor/codeigniter4/settings/src/Database/Migrations/`

1. **2021-07-04-041948_CreateSettingsTable** (Batch 3)
2. **2021-11-14-143905_AddContextColumn** (Batch 3)

---

## Migration Batches

| Batch | Date                | Migrations                    | Purpose            |
| ----- | ------------------- | ----------------------------- | ------------------ |
| 1     | 2026-01-02 16:55:40 | Original app migrations       | Initial setup      |
| 2     | 2026-01-02 17:17:47 | Shield create_auth_tables     | Shield integration |
| 3     | 2026-01-02 17:18:02 | Settings migrations           | Settings library   |
| 4     | 2026-01-13 14:32:22 | FixUserAppsActivityForeignKey | Document FK fix    |

---

## Key Changes Made

### 2026-01-13: Database Cleanup

1. **Foreign Key Fix**

   ```php
   // Before (incorrect - referenced legacy table)
   FOREIGN KEY (id_user) REFERENCES legacy_users(id)

   // After (correct - references Shield)
   FOREIGN KEY (id_user) REFERENCES users(id)
   ```

2. **Tables Dropped**

   - `legacy_users` - Old user table (replaced by Shield)
   - `legacy_settings` - Old settings table

3. **Migrations Archived**

   - Moved obsolete migrations to `archived/` folder
   - Renamed with `.legacy` extension
   - Created documentation README

4. **New Migration Created**
   - `2026-01-13-071500_FixUserAppsActivityForeignKey.php`
   - Documents the manual foreign key fix
   - Idempotent - safe for fresh installations

---

## For Fresh Installations

When running migrations on a fresh database:

1. **All active migrations will run in order**
2. **LogActivity migration already has correct foreign key** (Line 82)
3. **FixUserAppsActivityForeignKey will detect** no fix is needed (table has correct FK)
4. **Result**: Clean Shield-integrated database from the start

```bash
php spark migrate
```

---

## For Existing Installations

If you already have the database setup:

1. Archived migrations won't affect existing data
2. New migration (FixUserAppsActivityForeignKey) will run automatically
3. It checks if fix is needed before applying changes

---

## Verification

Check migration status:

```bash
php spark migrate:status
```

Expected result: 11 migrations ran (8 app + 1 shield + 2 settings)

---

## Related Files

- [LogActivity.php:82](file:///D:/DEV/PHP/codeigniter4_RBAC_boilerplate/app/Database/Migrations/2025-11-19-204424_LogActivity.php#L82) - Correct foreign key reference
- [Archived README](file:///D:/DEV/PHP/codeigniter4_RBAC_boilerplate/app/Database/Migrations/archived/README.md) - Explanation of archived migrations
- [FixUserAppsActivityForeignKey.php](file:///D:/DEV/PHP/codeigniter4_RBAC_boilerplate/app/Database/Migrations/2026-01-13-071500_FixUserAppsActivityForeignKey.php) - Documentation migration
