# Roles & Permissions System Implementation Guide

## Overview

The Lobby now has a comprehensive roles and capabilities system built on **Spatie Laravel Permission** package. This system provides:

- Role-based access control (RBAC)
- Fine-grained permissions
- Middleware for route protection
- Blade directives for UI-level authorization
- Admin UI for managing roles and permissions

---

## Architecture

### Core Components

1. **Spatie Permission Package** (v6.23.0)
   - Provides `Role` and `Permission` models
   - `HasRoles` trait added to User model
   - Database tables: `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`

2. **Middleware**
   - `RoleMiddleware`: Protect routes by role(s)
   - `PermissionMiddleware`: Protect routes by permission(s)

3. **Service Provider**
   - `PermissionServiceProvider`: Registers Gates and Blade directives

4. **Livewire Components**
   - Roles CRUD (Index, Create, Edit)
   - Permissions managed within role creation/editing

---

## Existing Roles & Permissions

### Roles

1. **Admin** (Super Admin)
   - Has ALL permissions
   - Cannot be deleted or renamed
   - Full system access

2. **Landlord**
   - Manage own properties
   - View/create/edit properties, units, tenants
   - View/create/edit contracts, invoices, payments
   - Manage maintenance requests
   - View reports

3. **Tenant**
   - View contracts and sign them
   - View invoices and make payments
   - Create maintenance requests
   - Send/receive messages

4. **Service Provider**
   - View/accept/complete maintenance jobs
   - View maintenance requests
   - Communication

### Permissions (60+ total)

**User Management**
- `view-users`, `create-users`, `edit-users`, `delete-users`

**Property Management**
- `view-properties`, `create-properties`, `edit-properties`, `delete-properties`, `manage-own-properties`

**Unit Management**
- `view-units`, `create-units`, `edit-units`, `delete-units`

**Tenant Management**
- `view-tenants`, `create-tenants`, `edit-tenants`, `delete-tenants`

**Lease Contracts**
- `view-contracts`, `create-contracts`, `edit-contracts`, `delete-contracts`, `sign-contracts`

**Financial**
- `view-invoices`, `create-invoices`, `edit-invoices`, `delete-invoices`
- `view-payments`, `create-payments`, `verify-payments`

**Maintenance**
- `view-maintenance-requests`, `create-maintenance-requests`, `edit-maintenance-requests`, `delete-maintenance-requests`
- `view-maintenance-jobs`, `assign-maintenance-jobs`, `accept-maintenance-jobs`, `complete-maintenance-jobs`

**Service Providers**
- `view-service-providers`, `create-service-providers`, `edit-service-providers`, `delete-service-providers`

**Communication**
- `send-messages`, `view-messages`, `view-notifications`

**System**
- `view-settings`, `edit-settings`, `view-audit-logs`, `view-reports`, `export-reports`

---

## Usage

### Middleware Usage

**Protect routes by role:**

```php
// Single role
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/settings', [SettingsController::class, 'index']);
});

// Multiple roles (user needs ANY of these)
Route::middleware(['auth', 'role:admin,landlord'])->group(function () {
    Route::get('/properties', [PropertiesController::class, 'index']);
});
```

**Protect routes by permission:**

```php
// Single permission
Route::middleware(['auth', 'permission:view-reports'])->group(function () {
    Route::get('/reports', [ReportsController::class, 'index']);
});

// Multiple permissions (user needs ANY of these)
Route::middleware(['auth', 'permission:edit-properties,manage-own-properties'])->group(function () {
    Route::get('/properties/{id}/edit', [PropertiesController::class, 'edit']);
});
```

### Blade Directives

**Check user role in views:**

```blade
@role('admin')
    <a href="/admin/settings">System Settings</a>
@endrole

@anyrole('admin', 'landlord')
    <a href="/properties/create">Create Property</a>
@endanyrole
```

**Check user permission in views:**

```blade
@permission('edit-users')
    <button>Edit User</button>
@endpermission

@anypermission('view-reports', 'export-reports')
    <a href="/reports">View Reports</a>
@endanypermission
```

### Controller/Component Usage

**Check roles:**

```php
if (auth()->user()->hasRole('admin')) {
    // Admin-only code
}

if (auth()->user()->hasAnyRole(['admin', 'landlord'])) {
    // Code for admin or landlord
}
```

**Check permissions:**

```php
if (auth()->user()->can('edit-properties')) {
    // Allow editing
}

if (auth()->user()->hasPermissionTo('view-reports')) {
    // Show reports
}
```

**Assign roles to users:**

```php
$user->assignRole('landlord');
$user->assignRole(['tenant', 'landlord']); // Multiple roles

$user->syncRoles(['landlord']); // Replace all roles
```

**Assign permissions directly to users:**

```php
$user->givePermissionTo('edit-properties');
$user->revokePermissionTo('delete-properties');
```

### Gates (Super Admin Bypass)

The `PermissionServiceProvider` includes a Gate that gives admins ALL permissions:

```php
Gate::before(function ($user, $ability) {
    if ($user->hasRole('admin')) {
        return true; // Admin can do everything
    }
});
```

---

## Admin UI for Managing Roles

### Routes (Still need to be added to admin.php)

```php
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin/roles')->name('admin.roles.')->group(function () {
    Route::get('/', \App\Livewire\Admin\Roles\Index::class)->name('index');
    Route::get('/create', \App\Livewire\Admin\Roles\Create::class)->name('create');
    Route::get('/{role}/edit', \App\Livewire\Admin\Roles\Edit::class)->name('edit');
});
```

### Features

**Roles Index (`/admin/roles`)**
- List all roles with:
  - Role name
  - Permission count
  - User count
  - Created date
- Search functionality
- Edit/Delete actions
- Cannot delete `admin` role
- Cannot delete roles with assigned users

**Create Role (`/admin/roles/create`)**
- Role name input
- Permissions grouped by category:
  - Users
  - Properties
  - Units
  - Tenants
  - Contracts
  - Financial
  - Maintenance
  - Service Providers
  - Communication
  - System
- Checkbox selection for each permission
- Form validation

**Edit Role (`/admin/roles/{id}/edit`)**
- Update role name
- Modify assigned permissions
- Cannot rename `admin` role
- Sync permissions on save

---

## Remaining Implementation Tasks

### 1. Complete View Files

Create views for:
- `resources/views/livewire/admin/roles/create.blade.php` ✅ (scaffolded, needs content)
- `resources/views/livewire/admin/roles/edit.blade.php` ✅ (scaffolded, needs content)

**Create View Structure:**
```blade
<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form wire:submit.prevent="save">
                <!-- Role Name Input -->
                <input wire:model="name" type="text" placeholder="Role Name">
                @error('name') <span class="error">{{ $message }}</span> @enderror

                <!-- Permissions by Group -->
                @foreach($permissionGroups as $group => $permissions)
                    <div class="permission-group">
                        <h3>{{ $group }}</h3>
                        @foreach($permissions as $permission)
                            <label>
                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}">
                                {{ ucwords(str_replace('-', ' ', $permission->name)) }}
                            </label>
                        @endforeach
                    </div>
                @endforeach

                <button type="submit">Create Role</button>
            </form>
        </div>
    </div>
</div>
```

### 2. Add Routes to admin.php

Add after existing routes:

```php
// Roles & Permissions Management
Route::middleware(['role:admin'])->prefix('roles')->name('roles.')->group(function () {
    Route::get('/', \App\Livewire\Admin\Roles\Index::class)->name('index');
    Route::get('/create', \App\Livewire\Admin\Roles\Create::class)->name('create');
    Route::get('/{role}/edit', \App\Livewire\Admin\Roles\Edit::class)->name('edit');
});
```

### 3. Add to Admin Sidebar Navigation

In `resources/views/layouts/admin.blade.php`, add menu item:

```blade
<!-- After System Settings -->
<a href="{{ route('admin.roles.index') }}" 
   class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-800 hover:text-white transition-colors duration-200 {{ request()->routeIs('admin.roles.*') ? 'bg-gray-800 text-white border-l-4 border-blue-500' : '' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <span>Roles & Permissions</span>
</a>
```

### 4. Apply Middleware to Existing Routes

Update `routes/admin.php` to protect routes by permission:

```php
// Properties Management
Route::middleware(['permission:view-properties'])->prefix('properties')->name('properties.')->group(function () {
    Route::get('/', PropertiesIndex::class)->name('index');
    Route::middleware(['permission:create-properties'])->get('/create', PropertiesCreate::class)->name('create');
    Route::get('/{property}', PropertiesShow::class)->name('show');
    Route::middleware(['permission:edit-properties'])->get('/{property}/edit', PropertiesEdit::class)->name('edit');
});

// Similarly for other modules...
```

### 5. Update Livewire Components

Add authorization checks in components:

```php
public function mount()
{
    $this->authorize('view-properties');
}

public function delete($id)
{
    $this->authorize('delete-properties');
    // Delete logic
}
```

### 6. Run Seeder

Seed the database with roles and permissions:

```bash
php artisan db:seed --class=RoleAndPermissionSeeder
```

Or add to `DatabaseSeeder.php`:

```php
public function run()
{
    $this->call(RoleAndPermissionSeeder::class);
}
```

### 7. Update User Creation

When creating users, assign default role:

```php
$user = User::create([...]);
$user->assignRole('tenant'); // Default role
```

### 8. Testing Checklist

- [ ] Admin can access all pages
- [ ] Landlord can access properties but not system settings
- [ ] Tenant can only access their contracts and payments
- [ ] Service provider can only access maintenance jobs
- [ ] Roles CRUD works correctly
- [ ] Permission updates reflect immediately
- [ ] Cannot delete admin role
- [ ] Cannot delete roles with users assigned
- [ ] Blade directives hide/show elements correctly
- [ ] Middleware redirects unauthorized users

---

## Security Considerations

1. **Super Admin Protection**: Admin role cannot be deleted or renamed
2. **Role Deletion**: Roles with assigned users cannot be deleted
3. **Permission Sync**: When updating roles, use `syncPermissions()` to avoid duplicates
4. **Cache**: Spatie Permission caches permissions - clear cache after changes:
   ```bash
   php artisan permission:cache-reset
   ```
5. **Middleware Order**: Always use `auth` middleware before `role` or `permission`
6. **Direct Permissions**: Avoid giving permissions directly to users - use roles instead

---

## Database Schema

```sql
-- roles table
id, name, guard_name, created_at, updated_at

-- permissions table
id, name, guard_name, created_at, updated_at

-- model_has_roles (pivot)
role_id, model_type, model_id

-- model_has_permissions (pivot)
permission_id, model_type, model_id

-- role_has_permissions (pivot)
permission_id, role_id
```

---

## Example: Protecting Admin Routes

**Current admin.php:**
```php
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    // ... other routes
});
```

**Enhanced with roles:**
```php
Route::middleware(['auth', 'verified', 'role:admin,landlord'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/users', UsersIndex::class)->name('users.index');
        Route::get('/roles', RolesIndex::class)->name('roles.index');
        Route::get('/settings', SettingsIndex::class)->name('settings.index');
    });

    // Landlord & Admin routes
    Route::middleware(['permission:view-properties'])->group(function () {
        Route::get('/properties', PropertiesIndex::class)->name('properties.index');
    });
});
```

---

## Next Steps

1. ✅ Middleware created
2. ✅ Service provider created
3. ✅ Livewire components scaffolded
4. ✅ Roles index view created
5. ⏳ Complete create/edit views with permission checkboxes
6. ⏳ Add routes to admin.php
7. ⏳ Add navigation menu item
8. ⏳ Apply middleware to all admin routes
9. ⏳ Run seeder on production
10. ⏳ Test all roles and permissions
11. ⏳ Update user creation to assign default roles

---

## Helpful Commands

```bash
# Create a new role
php artisan tinker
>>> $role = \Spatie\Permission\Models\Role::create(['name' => 'manager']);

# Create a new permission
>>> $permission = \Spatie\Permission\Models\Permission::create(['name' => 'view-dashboard']);

# Assign permission to role
>>> $role->givePermissionTo('view-dashboard');

# Assign role to user
>>> $user = \App\Models\User::find(1);
>>> $user->assignRole('admin');

# Check user role
>>> $user->hasRole('admin'); // true/false

# Check user permission
>>> $user->can('view-dashboard'); // true/false

# Clear permission cache
php artisan permission:cache-reset
```

---

## File Summary

**Created:**
- `app/Http/Middleware/RoleMiddleware.php`
- `app/Http/Middleware/PermissionMiddleware.php`
- `app/Providers/PermissionServiceProvider.php`
- `app/Livewire/Admin/Roles/Index.php`
- `app/Livewire/Admin/Roles/Create.php`
- `app/Livewire/Admin/Roles/Edit.php`
- `resources/views/livewire/admin/roles/index.blade.php`
- `resources/views/livewire/admin/roles/create.blade.php` (needs content)
- `resources/views/livewire/admin/roles/edit.blade.php` (needs content)

**Modified:**
- `app/Http/Kernel.php` (added middleware aliases)
- `config/app.php` (registered PermissionServiceProvider)

**Existing:**
- `database/seeders/RoleAndPermissionSeeder.php` (already created)
- `database/migrations/*_create_permission_tables.php` (already exists)
- `app/Models/User.php` (already has HasRoles trait)

