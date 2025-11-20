<?php

namespace Database\Seeders;

use App\Models\PropertyProvider;
use App\Models\Property;
use App\Models\ServiceProvider;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\MaintenanceRequest;
use App\Models\MaintenanceJob;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class MultiTenancyTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🧪 Creating Multi-Tenancy Test Data...');

        // Step 1: Create Property Providers
        $this->command->info('1️⃣  Creating Property Providers...');

        $providerA = PropertyProvider::create([
            'uuid' => Str::uuid(),
            'company_name' => 'ABC Property Management',
            'business_registration' => 'ABC-2025-001',
            'tax_number' => 'TAX-ABC-001',
            'contact_name' => 'John Smith',
            'contact_email' => 'john@abcproperties.com',
            'contact_phone' => '+97150111111',
            'subscription_tier' => 'professional',
            'status' => 'active',
            'properties_count' => 0,
        ]);

        $providerB = PropertyProvider::create([
            'uuid' => Str::uuid(),
            'company_name' => 'XYZ Property Services',
            'business_registration' => 'XYZ-2025-002',
            'tax_number' => 'TAX-XYZ-002',
            'contact_name' => 'Sarah Johnson',
            'contact_email' => 'sarah@xyzproperties.com',
            'contact_phone' => '+97150222222',
            'subscription_tier' => 'enterprise',
            'status' => 'active',
            'properties_count' => 0,
        ]);

        $this->command->info("   ✅ Created: {$providerA->company_name}");
        $this->command->info("   ✅ Created: {$providerB->company_name}");

        // Step 2: Create Landlord Users
        $this->command->info('2️⃣  Creating Landlord Users...');

        $landlordA = User::create([
            'uuid' => Str::uuid(),
            'name' => 'John Landlord',
            'email' => 'john.landlord@abcproperties.com',
            'password' => Hash::make('password'),
            'user_type' => 'landlord',
            'status' => 'active',
        ]);

        $landlordB = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Sarah Landlord',
            'email' => 'sarah.landlord@xyzproperties.com',
            'password' => Hash::make('password'),
            'user_type' => 'landlord',
            'status' => 'active',
        ]);

        $this->command->info("   ✅ Created landlord users");

        // Step 3: Create Properties
        $this->command->info('3️⃣  Creating Properties...');

        // Provider A Properties
        $propertyA1 = Property::create([
            'uuid' => Str::uuid(),
            'owner_id' => $landlordA->id,
            'property_provider_id' => $providerA->id,
            'property_code' => 'PROP-A-001',
            'name' => 'Sunset Apartments',
            'type' => 'residential',
            'address_line_1' => '123 Main Street',
            'city' => 'Dubai',
            'state' => 'Dubai',
            'country' => 'UAE',
            'status' => 'active',
        ]);

        $propertyA2 = Property::create([
            'uuid' => Str::uuid(),
            'owner_id' => $landlordA->id,
            'property_provider_id' => $providerA->id,
            'property_code' => 'PROP-A-002',
            'name' => 'Marina Towers',
            'type' => 'residential',
            'address_line_1' => '456 Beach Road',
            'city' => 'Dubai',
            'state' => 'Dubai',
            'country' => 'UAE',
            'status' => 'active',
        ]);

        // Provider B Properties
        $propertyB1 = Property::create([
            'uuid' => Str::uuid(),
            'owner_id' => $landlordB->id,
            'property_provider_id' => $providerB->id,
            'property_code' => 'PROP-B-001',
            'name' => 'Downtown Plaza',
            'type' => 'commercial',
            'address_line_1' => '789 City Center',
            'city' => 'Abu Dhabi',
            'state' => 'Abu Dhabi',
            'country' => 'UAE',
            'status' => 'active',
        ]);

        $propertyB2 = Property::create([
            'uuid' => Str::uuid(),
            'owner_id' => $landlordB->id,
            'property_provider_id' => $providerB->id,
            'property_code' => 'PROP-B-002',
            'name' => 'Garden Residences',
            'type' => 'residential',
            'address_line_1' => '321 Green Avenue',
            'city' => 'Abu Dhabi',
            'state' => 'Abu Dhabi',
            'country' => 'UAE',
            'status' => 'active',
        ]);

        $this->command->info("   ✅ Created 2 properties for {$providerA->company_name}");
        $this->command->info("   ✅ Created 2 properties for {$providerB->company_name}");

        // Update property counts
        $providerA->update(['properties_count' => 2]);
        $providerB->update(['properties_count' => 2]);

        // Step 4: Get Service Categories
        $this->command->info('4️⃣  Getting Service Categories...');

        $plumbing = ServiceCategory::where('slug', 'plumbing')->first();
        $electrical = ServiceCategory::where('slug', 'electrical')->first();

        if (!$plumbing || !$electrical) {
            $this->command->error('❌ Service categories not found. Run ServiceCategoriesSeeder first.');
            return;
        }

        $this->command->info("   ✅ Found: Plumbing (ID: {$plumbing->id})");
        $this->command->info("   ✅ Found: Electrical (ID: {$electrical->id})");

        // Step 5: Create Service Provider Users
        $this->command->info('5️⃣  Creating Service Providers...');

        $user1 = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Mike Plumber',
            'email' => 'mike@plumbing.com',
            'password' => Hash::make('password'),
            'user_type' => 'service_provider',
            'status' => 'active',
        ]);

        $serviceProvider1 = ServiceProvider::create([
            'uuid' => Str::uuid(),
            'user_id' => $user1->id,
            'provider_code' => 'SP-001',
            'company_name' => 'Mike\'s Plumbing Services',
            'contact_person' => 'Mike Plumber',
            'contact_email' => 'mike@plumbing.com',
            'contact_phone' => '+97150333333',
            'status' => 'active',
        ]);

        // Assign to Plumbing category
        $serviceProvider1->serviceCategories()->attach($plumbing->id, [
            'is_primary' => true,
            'is_certified' => true,
            'experience_years' => 10,
        ]);

        // Assign to Provider A only
        $providerA->serviceProviders()->attach($serviceProvider1->id, [
            'status' => 'active',
            'is_preferred' => true,
            'priority' => 1,
        ]);

        $this->command->info("   ✅ Created: {$serviceProvider1->company_name} (Provider A, Plumbing)");

        $user2 = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Sarah Electrician',
            'email' => 'sarah@electrical.com',
            'password' => Hash::make('password'),
            'user_type' => 'service_provider',
            'status' => 'active',
        ]);

        $serviceProvider2 = ServiceProvider::create([
            'uuid' => Str::uuid(),
            'user_id' => $user2->id,
            'provider_code' => 'SP-002',
            'company_name' => 'Sarah\'s Electrical Works',
            'contact_person' => 'Sarah Electrician',
            'contact_email' => 'sarah@electrical.com',
            'contact_phone' => '+97150444444',
            'status' => 'active',
        ]);

        // Assign to Electrical category
        $serviceProvider2->serviceCategories()->attach($electrical->id, [
            'is_primary' => true,
            'is_certified' => true,
            'experience_years' => 8,
        ]);

        // Assign to Provider B only
        $providerB->serviceProviders()->attach($serviceProvider2->id, [
            'status' => 'active',
            'is_preferred' => true,
            'priority' => 1,
        ]);

        $this->command->info("   ✅ Created: {$serviceProvider2->company_name} (Provider B, Electrical)");

        $user3 = User::create([
            'uuid' => Str::uuid(),
            'name' => 'Alex Multi-Skill',
            'email' => 'alex@multiskill.com',
            'password' => Hash::make('password'),
            'user_type' => 'service_provider',
            'status' => 'active',
        ]);

        $serviceProvider3 = ServiceProvider::create([
            'uuid' => Str::uuid(),
            'user_id' => $user3->id,
            'provider_code' => 'SP-003',
            'company_name' => 'Alex Multi-Skill Services',
            'contact_person' => 'Alex Multi-Skill',
            'contact_email' => 'alex@multiskill.com',
            'contact_phone' => '+97150555555',
            'status' => 'active',
        ]);

        // Assign to both categories
        $serviceProvider3->serviceCategories()->attach($plumbing->id, [
            'is_primary' => true,
            'is_certified' => true,
            'experience_years' => 12,
        ]);
        $serviceProvider3->serviceCategories()->attach($electrical->id, [
            'is_primary' => false,
            'is_certified' => true,
            'experience_years' => 5,
        ]);

        // Assign to both providers
        $providerA->serviceProviders()->attach($serviceProvider3->id, [
            'status' => 'active',
            'is_preferred' => false,
            'priority' => 2,
        ]);
        $providerB->serviceProviders()->attach($serviceProvider3->id, [
            'status' => 'active',
            'is_preferred' => false,
            'priority' => 2,
        ]);

        $this->command->info("   ✅ Created: {$serviceProvider3->company_name} (Both Providers, Plumbing & Electrical)");

        // Activate categories for property providers
        $providerA->serviceCategories()->attach($plumbing->id, ['is_active' => true]);
        $providerA->serviceCategories()->attach($electrical->id, ['is_active' => true]);
        $providerB->serviceCategories()->attach($plumbing->id, ['is_active' => true]);
        $providerB->serviceCategories()->attach($electrical->id, ['is_active' => true]);

        // Step 6: Create Maintenance Requests
        $this->command->info('6️⃣  Creating Maintenance Requests...');

        // Provider A - Plumbing requests
        $requestA1 = MaintenanceRequest::create([
            'uuid' => Str::uuid(),
            'request_number' => 'MR-TEST-A1',
            'property_id' => $propertyA1->id,
            'service_category_id' => $plumbing->id,
            'title' => 'Leaking Faucet',
            'description' => 'Kitchen faucet is leaking',
            'category' => 'plumbing',
            'priority' => 'medium',
            'status' => 'approved',
        ]);

        $requestA2 = MaintenanceRequest::create([
            'uuid' => Str::uuid(),
            'request_number' => 'MR-TEST-A2',
            'property_id' => $propertyA2->id,
            'service_category_id' => $plumbing->id,
            'title' => 'Clogged Drain',
            'description' => 'Bathroom drain is clogged',
            'category' => 'plumbing',
            'priority' => 'high',
            'status' => 'approved',
        ]);

        // Provider B - Electrical requests
        $requestB1 = MaintenanceRequest::create([
            'uuid' => Str::uuid(),
            'request_number' => 'MR-TEST-B1',
            'property_id' => $propertyB1->id,
            'service_category_id' => $electrical->id,
            'title' => 'Faulty Outlet',
            'description' => 'Electrical outlet not working',
            'category' => 'electrical',
            'priority' => 'high',
            'status' => 'approved',
        ]);

        $requestB2 = MaintenanceRequest::create([
            'uuid' => Str::uuid(),
            'request_number' => 'MR-TEST-B2',
            'property_id' => $propertyB2->id,
            'service_category_id' => $electrical->id,
            'title' => 'Light Fixture Replacement',
            'description' => 'Replace broken light fixture',
            'category' => 'electrical',
            'priority' => 'medium',
            'status' => 'approved',
        ]);

        // Cross-category requests
        $requestB3 = MaintenanceRequest::create([
            'uuid' => Str::uuid(),
            'request_number' => 'MR-TEST-B3',
            'property_id' => $propertyB1->id,
            'service_category_id' => $plumbing->id,
            'title' => 'Water Heater Issue',
            'description' => 'Water heater not heating properly',
            'category' => 'plumbing',
            'priority' => 'high',
            'status' => 'approved',
        ]);

        $requestA3 = MaintenanceRequest::create([
            'uuid' => Str::uuid(),
            'request_number' => 'MR-TEST-A3',
            'property_id' => $propertyA1->id,
            'service_category_id' => $electrical->id,
            'title' => 'Circuit Breaker Tripping',
            'description' => 'Circuit breaker keeps tripping',
            'category' => 'electrical',
            'priority' => 'urgent',
            'status' => 'approved',
        ]);

        $this->command->info("   ✅ Created 6 maintenance requests");

        // Step 7: Create Maintenance Jobs
        $this->command->info('7️⃣  Creating Maintenance Jobs...');

        $job1 = MaintenanceJob::create([
            'uuid' => Str::uuid(),
            'job_number' => 'MJ-TEST-001',
            'maintenance_request_id' => $requestA1->id,
            'service_provider_id' => $serviceProvider1->id,
            'status' => 'assigned',
            'priority' => 'medium',
        ]);

        $job2 = MaintenanceJob::create([
            'uuid' => Str::uuid(),
            'job_number' => 'MJ-TEST-002',
            'maintenance_request_id' => $requestB1->id,
            'service_provider_id' => $serviceProvider2->id,
            'status' => 'assigned',
            'priority' => 'high',
        ]);

        $this->command->info("   ✅ Created 2 maintenance jobs");

        // Summary
        $this->command->newLine();
        $this->command->info('✅ Test Data Creation Complete!');
        $this->command->newLine();
        $this->command->table(
            ['Component', 'Count', 'Details'],
            [
                ['Property Providers', 2, 'ABC Property Management, XYZ Property Services'],
                ['Landlord Users', 2, 'John & Sarah (owners)'],
                ['Properties', 4, '2 for each provider'],
                ['Service Providers', 3, 'SP1 (Provider A, Plumbing), SP2 (Provider B, Electrical), SP3 (Both, Both)'],
                ['Maintenance Requests', 6, '3 per provider, mixed categories'],
                ['Maintenance Jobs', 2, 'Initial assigned jobs'],
            ]
        );
        $this->command->newLine();
        $this->command->info('🧪 Test Credentials:');
        $this->command->info('   - mike@plumbing.com / password (Provider A, Plumbing)');
        $this->command->info('   - sarah@electrical.com / password (Provider B, Electrical)');
        $this->command->info('   - alex@multiskill.com / password (Both Providers, Both Categories)');
        $this->command->info('   - john.landlord@abcproperties.com / password (Landlord A)');
        $this->command->info('   - sarah.landlord@xyzproperties.com / password (Landlord B)');
        $this->command->newLine();
    }
}
