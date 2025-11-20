// Setup test data for multi-tenancy testing
// Run with: php artisan tinker < setup_test_data.php

$users = DB::table('users')->whereIn('email', ['mike.test@plumbing.com', 'sarah.test@electrical.com', 'alex.test@multiskill.com'])->get();
$providerA = DB::table('property_providers')->where('company_name', 'ABC Test Properties')->first();
$providerB = DB::table('property_providers')->where('company_name', 'XYZ Test Services')->first();
$plumbing = DB::table('service_categories')->where('slug', 'plumbing')->first();
$electrical = DB::table('service_categories')->where('slug', 'electrical')->first();

echo "Setting up service providers...\n";

foreach($users as $u) {
    $code = 'TEST-SP-' . str_pad($u->id, 3, '0', STR_PAD_LEFT);
    $spId = DB::table('service_providers')->insertGetId([
        'uuid' => DB::raw('UUID()'),
        'user_id' => $u->id,
        'provider_code' => $code,
        'company_name' => str_replace('.test@', ' Test ', $u->email),
        'primary_contact_name' => $u->name,
        'primary_contact_email' => $u->email,
        'primary_contact_phone' => '+971501234567',
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    if(strpos($u->email, 'plumbing') !== false) {
        DB::table('service_provider_categories')->insert([
            'service_provider_id' => $spId,
            'service_category_id' => $plumbing->id,
            'is_primary' => 1,
            'is_certified' => 1,
            'experience_years' => 10,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('property_provider_service_providers')->insert([
            'property_provider_id' => $providerA->id,
            'service_provider_id' => $spId,
            'status' => 'active',
            'is_preferred' => 1,
            'priority' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Created: $code (Provider A, Plumbing)\n";
    }
    elseif(strpos($u->email, 'electrical') !== false) {
        DB::table('service_provider_categories')->insert([
            'service_provider_id' => $spId,
            'service_category_id' => $electrical->id,
            'is_primary' => 1,
            'is_certified' => 1,
            'experience_years' => 8,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('property_provider_service_providers')->insert([
            'property_provider_id' => $providerB->id,
            'service_provider_id' => $spId,
            'status' => 'active',
            'is_preferred' => 1,
            'priority' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        echo "Created: $code (Provider B, Electrical)\n";
    }
    elseif(strpos($u->email, 'multiskill') !== false) {
        DB::table('service_provider_categories')->insert([
            [
                'service_provider_id' => $spId,
                'service_category_id' => $plumbing->id,
                'is_primary' => 1,
                'is_certified' => 1,
                'experience_years' => 12,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'service_provider_id' => $spId,
                'service_category_id' => $electrical->id,
                'is_primary' => 0,
                'is_certified' => 1,
                'experience_years' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
        DB::table('property_provider_service_providers')->insert([
            [
                'property_provider_id' => $providerA->id,
                'service_provider_id' => $spId,
                'status' => 'active',
                'is_preferred' => 0,
                'priority' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'property_provider_id' => $providerB->id,
                'service_provider_id' => $spId,
                'status' => 'active',
                'is_preferred' => 0,
                'priority' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
        echo "Created: $code (Both Providers, Both Categories)\n";
    }
}

// Activate categories for providers
try {
    DB::table('property_active_categories')->insert([
        [
            'property_provider_id' => $providerA->id,
            'service_category_id' => $plumbing->id,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'property_provider_id' => $providerA->id,
            'service_category_id' => $electrical->id,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'property_provider_id' => $providerB->id,
            'service_category_id' => $plumbing->id,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'property_provider_id' => $providerB->id,
            'service_category_id' => $electrical->id,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]
    ]);
    echo "Categories activated for both providers\n";
} catch (Exception $e) {
    echo "Categories already activated or error: " . substr($e->getMessage(), 0, 100) . "\n";
}

// Create maintenance requests
$properties = DB::table('properties')->where('property_code', 'LIKE', 'TEST-%')->get();
echo "\nCreating maintenance requests for " . count($properties) . " properties...\n";

$requests = [];
foreach($properties as $prop) {
    $isProviderA = strpos($prop->property_code, 'TEST-PROP-A') === 0;
    $categoryId = ($prop->property_code === 'TEST-PROP-A-001' || $prop->property_code === 'TEST-PROP-B-003') ? $plumbing->id : $electrical->id;
    $category = ($categoryId === $plumbing->id) ? 'plumbing' : 'electrical';

    DB::table('maintenance_requests')->insert([
        'uuid' => DB::raw('UUID()'),
        'request_number' => 'TEST-MR-' . substr($prop->property_code, -3),
        'property_id' => $prop->id,
        'service_category_id' => $categoryId,
        'title' => 'Test Issue for ' . $prop->name,
        'description' => 'Test maintenance request',
        'category' => $category,
        'priority' => 'normal',
        'status' => 'approved',
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

echo "Test data setup complete!\n";
echo "\n=== Test Credentials ===\n";
echo "mike.test@plumbing.com / password (Provider A, Plumbing)\n";
echo "sarah.test@electrical.com / password (Provider B, Electrical)\n";
echo "alex.test@multiskill.com / password (Both Providers, Both Categories)\n";
