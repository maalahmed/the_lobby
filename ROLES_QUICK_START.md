# Roles & Permissions Quick Start Guide

## Accessing the Roles Management

1. **Login as Admin**: Only users with the `admin` role can access roles management
2. **Navigate**: Click "Roles & Permissions" in the sidebar (visible only to admins)
3. **URL**: `https://thelobbys.mostech.net/admin/roles`

---

## Current System Status (Production)

✅ **4 Roles Created:**
- `admin` - Super Admin (all permissions)
- `landlord` - Property owner
- `tenant` - Property renter
- `service_provider` - Maintenance provider

✅ **49 Permissions Defined** across 10 categories:
- Users, Properties, Units, Tenants, Contracts
- Financial, Maintenance, Service Providers
- Communication, System

---

## Quick Actions

### Create a New Role

1. Go to `/admin/roles`
2. Click "Create Role" button
3. Enter role name (lowercase, e.g., "manager")
4. Select permissions by category
5. Click "Create Role"

**Example Use Cases:**
- **Property Manager**: Can manage properties but not system settings
- **Accountant**: Can view/create invoices and payments but not properties
- **Maintenance Supervisor**: Can assign/manage maintenance jobs

### Edit Existing Role

1. Go to `/admin/roles`
2. Click "Edit" next to the role
3. Modify permissions (checkboxes are grouped)
4. See user count and selected permissions count
5. Click "Update Role"

**Note:** Cannot rename the `admin` role for security

### Delete a Role

1. Go to `/admin/roles`
2. Click "Delete" next to the role
3. Confirm deletion

**Restrictions:**
- Cannot delete `admin` role
- Cannot delete roles with users assigned

### Assign Role to User

Go to `/admin/users/{id}/edit` and select role from dropdown.

**Programmatically:**
```php
$user->assignRole('landlord');
```

---

## Using Permissions in Code

### In Routes (Middleware)

```php
// Require specific role
Route::middleware(['role:admin'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index']);
});

// Require specific permission
Route::middleware(['permission:view-reports'])->group(function () {
    Route::get('/reports', [ReportsController::class, 'index']);
});
```

### In Blade Views

```blade
@role('admin')
    <a href="/admin/settings">System Settings</a>
@endrole

@permission('edit-properties')
    <button>Edit Property</button>
@endpermission

@anyrole('admin', 'landlord')
    <a href="/properties/create">Create Property</a>
@endanyrole
```

### In Controllers/Components

```php
// Check role
if (auth()->user()->hasRole('admin')) {
    // Admin code
}

// Check permission
if (auth()->user()->can('edit-properties')) {
    // Allow editing
}

// Authorize (throws 403 if fails)
$this->authorize('view-properties');
```

---

## Permission Categories & Examples

### Users Management
- `view-users`, `create-users`, `edit-users`, `delete-users`

**Who needs it:** Admin, HR Manager

### Properties Management
- `view-properties`, `create-properties`, `edit-properties`, `delete-properties`
- `manage-own-properties` (landlords can manage their own)

**Who needs it:** Admin, Landlord, Property Manager

### Financial
- `view-invoices`, `create-invoices`, `edit-invoices`, `delete-invoices`
- `view-payments`, `create-payments`, `verify-payments`

**Who needs it:** Admin, Landlord, Accountant

### Maintenance
- `view-maintenance-requests`, `create-maintenance-requests`
- `view-maintenance-jobs`, `assign-maintenance-jobs`
- `accept-maintenance-jobs`, `complete-maintenance-jobs`

**Who needs it:** Admin, Landlord, Tenant (requests), Service Provider (jobs)

### System
- `view-settings`, `edit-settings`
- `view-audit-logs`
- `view-reports`, `export-reports`

**Who needs it:** Admin only

---

## Common Scenarios

### Scenario 1: New Property Manager Role

**Permissions needed:**
- ✅ Properties: view, create, edit
- ✅ Units: view, create, edit
- ✅ Tenants: view, create, edit
- ✅ Contracts: view, create, edit
- ✅ Maintenance Requests: view, create, edit, assign
- ❌ Users: none
- ❌ Settings: none
- ❌ Financial: view only

**Steps:**
1. Create role "property_manager"
2. Select permissions from Properties, Units, Tenants, Contracts, Maintenance categories
3. Exclude Users, Settings, Financial edit permissions
4. Assign to property manager users

### Scenario 2: Accountant Role

**Permissions needed:**
- ✅ Invoices: view, create, edit
- ✅ Payments: view, create, verify
- ✅ Reports: view, export
- ❌ Properties: view only
- ❌ Users: none
- ❌ Settings: none

**Steps:**
1. Create role "accountant"
2. Select only Financial and Reports permissions
3. Exclude all management permissions
4. Assign to accounting staff

### Scenario 3: Tenant with Maintenance Rights

**Default tenant role already has:**
- ✅ Create maintenance requests
- ✅ View own contracts
- ✅ View invoices, make payments
- ✅ Send/receive messages

**If a tenant needs to manage maintenance jobs (e.g., property manager who is also a tenant):**
1. Assign both "tenant" AND "property_manager" roles
2. User will have combined permissions

---

## Best Practices

### Role Naming
- ✅ Use lowercase, single words: `manager`, `accountant`, `supervisor`
- ❌ Avoid spaces or capitals: `Property Manager`, `ADMIN`

### Permission Assignment
- **Principle of Least Privilege**: Give only necessary permissions
- **Group Related Permissions**: Select entire categories when appropriate
- **Test Before Production**: Create test users to verify permissions work

### Security
- **Protect Admin Role**: Never delete or rename `admin`
- **Audit Regularly**: Check user roles quarterly
- **Remove Unused Roles**: Delete roles with 0 users
- **Clear Cache**: After bulk permission changes, run `php artisan permission:cache-reset`

### Organization
- **Document Custom Roles**: Keep a list of what each custom role does
- **Standardize**: Use same role names across environments
- **Review Permissions**: When adding new features, update relevant roles

---

## Troubleshooting

### "403 Unauthorized" Error

**Cause**: User doesn't have required role or permission

**Fix:**
1. Check user's assigned role in `/admin/users`
2. Verify role has the needed permission in `/admin/roles/{id}/edit`
3. Ensure middleware is checking correct permission name

### Role/Permission Changes Not Working

**Cause**: Permission cache not cleared

**Fix:**
```bash
php artisan permission:cache-reset
```

### Cannot See "Roles & Permissions" Menu

**Cause**: Only visible to users with `admin` role

**Fix:** Ensure you're logged in as admin user

### User Has Multiple Roles - Which Permissions Apply?

**Answer**: User gets ALL permissions from ALL assigned roles (cumulative)

**Example:**
- Tenant role: `view-contracts`, `create-payments`
- Landlord role: `view-properties`, `create-properties`
- User with BOTH roles: Has all 4 permissions

---

## API Reference

### Check if User Has Role
```php
auth()->user()->hasRole('admin')  // bool
auth()->user()->hasAnyRole(['admin', 'landlord'])  // bool
auth()->user()->hasAllRoles(['tenant', 'landlord'])  // bool
```

### Check if User Has Permission
```php
auth()->user()->can('edit-properties')  // bool
auth()->user()->hasPermissionTo('view-reports')  // bool
```

### Assign/Remove Roles
```php
$user->assignRole('landlord');
$user->syncRoles(['landlord', 'tenant']);  // Replace all roles
$user->removeRole('tenant');
```

### Assign/Remove Permissions (Direct - Not Recommended)
```php
$user->givePermissionTo('edit-properties');
$user->revokePermissionTo('delete-properties');
```

### Get User's Roles/Permissions
```php
$user->roles  // Collection of Role models
$user->permissions  // Collection of Permission models
$user->getAllPermissions()  // All permissions (from roles + direct)
```

---

## Next Steps

1. **Test the System**: Create a test user with each role
2. **Apply to Routes**: Add role/permission middleware to sensitive routes
3. **Update UI**: Hide/show buttons based on permissions
4. **Document Custom Roles**: If you create new roles, document their purpose
5. **Train Users**: Explain what each role can/cannot do

---

## Support

- **Documentation**: See `ROLES_PERMISSIONS_GUIDE.md` for technical details
- **Testing**: Run through scenarios in `TESTING_CHECKLIST.md`
- **Spatie Docs**: https://spatie.be/docs/laravel-permission

