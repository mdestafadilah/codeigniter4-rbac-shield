# Archived Migrations

This folder contains migrations that are no longer used due to Shield integration.

## Files

### 2025-08-05-050154_CreateUsersTable.php.legacy

**Reason:** This migration created the old custom `users` table which was later:

1. Renamed to `legacy_users` during Shield preparation
2. Data migrated to Shield's `users` and `auth_identities` tables
3. Dropped from database (2026-01-13)

**Replaced by:** CodeIgniter Shield migrations (`create_auth_tables`)

### 2025-08-05-053136_UpdateUsersTableForRBAC.php.legacy

**Reason:** This migration updated the old custom `users` table for RBAC, which is no longer needed as Shield handles authentication separately from RBAC.

**Note:** Custom RBAC tables (`roles`, `permissions`, `role_permissions`) are still in use and their migrations remain active.

## Shield Integration Timeline

1. **2025-08-05**: Original custom authentication system created
2. **2026-01-02**: Shield integration completed
   - Old `users` table renamed to `legacy_users`
   - Shield tables created (Batch 2)
3. **2026-01-13**: Legacy tables cleanup
   - Fixed `user_apps_activity` foreign key to reference Shield's `users` table
   - Dropped `legacy_users` and `legacy_settings` tables
   - Archived obsolete migrations

## Active Migrations

The following migrations are still active and should not be modified:

- `CreateMahasiswaTable` - Student data
- `CreateRolesTable` - Custom RBAC roles
- `CreatePermissionsTable` - Custom RBAC permissions
- `CreateRolePermissionsTable` - RBAC role-permission mapping
- `CreateSettingsTable` - Application settings
- `LogActivity` - User activity logging (references Shield users)
- `CreateMenusTable` - Menu management

Plus Shield's migrations from vendor:

- `create_auth_tables` (2020-12-28-223112)
