<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'view-users', 'create-users', 'edit-users', 'delete-users',
            
            // Property Management
            'view-properties', 'create-properties', 'edit-properties', 'delete-properties', 'manage-own-properties',
            
            // Unit Management
            'view-units', 'create-units', 'edit-units', 'delete-units',
            
            // Tenant Management
            'view-tenants', 'create-tenants', 'edit-tenants', 'delete-tenants',
            
            // Lease Contract Management
            'view-contracts', 'create-contracts', 'edit-contracts', 'delete-contracts', 'sign-contracts',
            
            // Financial Management
            'view-invoices', 'create-invoices', 'edit-invoices', 'delete-invoices',
            'view-payments', 'create-payments', 'verify-payments',
            
            // Maintenance Management
            'view-maintenance-requests', 'create-maintenance-requests', 'edit-maintenance-requests', 
            'delete-maintenance-requests', 'assign-maintenance-jobs', 'view-maintenance-jobs',
            'accept-maintenance-jobs', 'complete-maintenance-jobs',
            
            // Service Provider Management
            'view-service-providers', 'create-service-providers', 'edit-service-providers', 'delete-service-providers',
            
            // Communication
            'send-messages', 'view-messages', 'view-notifications',
            
            // System Settings
            'view-settings', 'edit-settings', 'view-audit-logs',
            
            // Reports
            'view-reports', 'export-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Landlord
        $landlordRole = Role::create(['name' => 'landlord']);
        $landlordRole->givePermissionTo([
            'manage-own-properties', 'view-properties', 'create-properties', 'edit-properties',
            'view-units', 'create-units', 'edit-units',
            'view-tenants', 'create-tenants', 'edit-tenants',
            'view-contracts', 'create-contracts', 'edit-contracts', 'sign-contracts',
            'view-invoices', 'create-invoices', 'edit-invoices',
            'view-payments', 'create-payments', 'verify-payments',
            'view-maintenance-requests', 'create-maintenance-requests', 'edit-maintenance-requests',
            'assign-maintenance-jobs', 'view-maintenance-jobs', 'view-service-providers',
            'send-messages', 'view-messages', 'view-notifications',
            'view-reports', 'export-reports',
        ]);

        // Tenant
        $tenantRole = Role::create(['name' => 'tenant']);
        $tenantRole->givePermissionTo([
            'view-contracts', 'sign-contracts', 'view-invoices', 'create-payments',
            'create-maintenance-requests', 'view-maintenance-requests',
            'send-messages', 'view-messages', 'view-notifications',
        ]);

        // Service Provider
        $providerRole = Role::create(['name' => 'service_provider']);
        $providerRole->givePermissionTo([
            'view-maintenance-jobs', 'accept-maintenance-jobs', 'complete-maintenance-jobs',
            'view-maintenance-requests', 'send-messages', 'view-messages', 'view-notifications',
        ]);
    }
}
