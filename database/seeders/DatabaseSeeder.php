<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Step 1: Roles and Permissions
            RoleAndPermissionSeeder::class,
            
            // Step 2: Users (creates UserProfiles automatically)
            UserSeeder::class,
            
            // Step 3: Properties
            PropertySeeder::class,
            
            // Step 4: Units
            UnitSeeder::class,
            
            // Step 5: Tenants
            TenantSeeder::class,
            
            // Step 6: Lease Contracts
            LeaseContractSeeder::class,
            
            // Step 7: Invoices
            InvoiceSeeder::class,
            
            // Step 8: Payments
            PaymentSeeder::class,
            
            // Step 9: Maintenance Requests
            MaintenanceRequestSeeder::class,
            
            // Step 10: Service Providers
            ServiceProviderSeeder::class,
            
            // Step 11: System Settings
            SystemSettingSeeder::class,
            
            // Step 12: Messages
            MessageSeeder::class,
            
            // Step 13: Notifications
            NotificationSeeder::class,
        ]);
    }
}
