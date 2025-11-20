-- Multi-Tenancy Test Data SQL Script
-- Run this script to create complete test data for multi-tenancy testing

-- Get service category IDs
SET @plumbing_id = (SELECT id FROM service_categories WHERE slug = 'plumbing' LIMIT 1);
SET @electrical_id = (SELECT id FROM service_categories WHERE slug = 'electrical' LIMIT 1);

-- Step 1: Create Property Providers (ABC and XYZ)
INSERT INTO property_providers (uuid, company_name, business_registration, tax_number, contact_name, contact_email, contact_phone, subscription_tier, status, properties_count, created_at, updated_at)
VALUES
(UUID(), 'ABC Test Properties', 'ABC-TEST-001', 'TAX-ABC-001', 'John Smith Test', 'john.test@abcproperties.com', '+97150111111', 'professional', 'active', 0, NOW(), NOW()),
(UUID(), 'XYZ Test Services', 'XYZ-TEST-002', 'TAX-XYZ-002', 'Sarah Johnson Test', 'sarah.test@xyzproperties.com', '+97150222222', 'enterprise', 'active', 0, NOW(), NOW());

-- Get the provider IDs
SET @provider_a_id = (SELECT id FROM property_providers WHERE company_name = 'ABC Test Properties' LIMIT 1);
SET @provider_b_id = (SELECT id FROM property_providers WHERE company_name = 'XYZ Test Services' LIMIT 1);

-- Step 2: Create Landlord Users
INSERT INTO users (uuid, name, email, password, user_type, status, created_at, updated_at)
VALUES
(UUID(), 'John Landlord Test', 'john.test.landlord@abc.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'landlord', 'active', NOW(), NOW()),
(UUID(), 'Sarah Landlord Test', 'sarah.test.landlord@xyz.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'landlord', 'active', NOW(), NOW());

-- Get landlord IDs
SET @landlord_a_id = (SELECT id FROM users WHERE email = 'john.test.landlord@abc.com' LIMIT 1);
SET @landlord_b_id = (SELECT id FROM users WHERE email = 'sarah.test.landlord@xyz.com' LIMIT 1);

-- Step 3: Create Properties
INSERT INTO properties (uuid, owner_id, property_provider_id, property_code, name, type, address_line_1, city, state, country, status, created_at, updated_at)
VALUES
-- Provider A Properties
(UUID(), @landlord_a_id, @provider_a_id, 'TEST-PROP-A-001', 'Sunset Test Apartments', 'residential', '123 Test Main Street', 'Dubai', 'Dubai', 'UAE', 'active', NOW(), NOW()),
(UUID(), @landlord_a_id, @provider_a_id, 'TEST-PROP-A-002', 'Marina Test Towers', 'residential', '456 Test Beach Road', 'Dubai', 'Dubai', 'UAE', 'active', NOW(), NOW()),
-- Provider B Properties
(UUID(), @landlord_b_id, @provider_b_id, 'TEST-PROP-B-001', 'Downtown Test Plaza', 'commercial', '789 Test City Center', 'Abu Dhabi', 'Abu Dhabi', 'UAE', 'active', NOW(), NOW()),
(UUID(), @landlord_b_id, @provider_b_id, 'TEST-PROP-B-002', 'Garden Test Residences', 'residential', '321 Test Green Avenue', 'Abu Dhabi', 'Abu Dhabi', 'UAE', 'active', NOW(), NOW());

-- Get property IDs
SET @property_a1_id = (SELECT id FROM properties WHERE property_code = 'TEST-PROP-A-001' LIMIT 1);
SET @property_a2_id = (SELECT id FROM properties WHERE property_code = 'TEST-PROP-A-002' LIMIT 1);
SET @property_b1_id = (SELECT id FROM properties WHERE property_code = 'TEST-PROP-B-001' LIMIT 1);
SET @property_b2_id = (SELECT id FROM properties WHERE property_code = 'TEST-PROP-B-002' LIMIT 1);

-- Update property counts
UPDATE property_providers SET properties_count = 2 WHERE id = @provider_a_id;
UPDATE property_providers SET properties_count = 2 WHERE id = @provider_b_id;

-- Step 4: Create Service Provider Users
INSERT INTO users (uuid, name, email, password, user_type, status, created_at, updated_at)
VALUES
(UUID(), 'Mike Test Plumber', 'mike.test@plumbing.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'service_provider', 'active', NOW(), NOW()),
(UUID(), 'Sarah Test Electrician', 'sarah.test@electrical.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'service_provider', 'active', NOW(), NOW()),
(UUID(), 'Alex Test MultiSkill', 'alex.test@multiskill.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'service_provider', 'active', NOW(), NOW());

-- Get service provider user IDs
SET @sp_user1_id = (SELECT id FROM users WHERE email = 'mike.test@plumbing.com' LIMIT 1);
SET @sp_user2_id = (SELECT id FROM users WHERE email = 'sarah.test@electrical.com' LIMIT 1);
SET @sp_user3_id = (SELECT id FROM users WHERE email = 'alex.test@multiskill.com' LIMIT 1);

-- Step 5: Create Service Provider Records
INSERT INTO service_providers (uuid, user_id, provider_code, company_name, contact_person, contact_email, contact_phone, status, created_at, updated_at)
VALUES
(UUID(), @sp_user1_id, 'TEST-SP-001', 'Mike Test Plumbing Services', 'Mike Test Plumber', 'mike.test@plumbing.com', '+97150333333', 'active', NOW(), NOW()),
(UUID(), @sp_user2_id, 'TEST-SP-002', 'Sarah Test Electrical Works', 'Sarah Test Electrician', 'sarah.test@electrical.com', '+97150444444', 'active', NOW(), NOW()),
(UUID(), @sp_user3_id, 'TEST-SP-003', 'Alex Test Multi-Skill Services', 'Alex Test MultiSkill', 'alex.test@multiskill.com', '+97150555555', 'active', NOW(), NOW());

-- Get service provider IDs
SET @sp1_id = (SELECT id FROM service_providers WHERE provider_code = 'TEST-SP-001' LIMIT 1);
SET @sp2_id = (SELECT id FROM service_providers WHERE provider_code = 'TEST-SP-002' LIMIT 1);
SET @sp3_id = (SELECT id FROM service_providers WHERE provider_code = 'TEST-SP-003' LIMIT 1);

-- Step 6: Assign Service Providers to Categories
INSERT INTO service_provider_categories (service_provider_id, service_category_id, is_primary, is_certified, certification_number, experience_years, created_at, updated_at)
VALUES
-- SP1: Plumbing only
(@sp1_id, @plumbing_id, 1, 1, 'CERT-PLUMB-001', 10, NOW(), NOW()),
-- SP2: Electrical only
(@sp2_id, @electrical_id, 1, 1, 'CERT-ELEC-001', 8, NOW(), NOW()),
-- SP3: Both categories
(@sp3_id, @plumbing_id, 1, 1, 'CERT-PLUMB-002', 12, NOW(), NOW()),
(@sp3_id, @electrical_id, 0, 1, 'CERT-ELEC-002', 5, NOW(), NOW());

-- Step 7: Assign Service Providers to Property Providers
INSERT INTO property_provider_service_providers (property_provider_id, service_provider_id, status, is_preferred, priority, created_at, updated_at)
VALUES
-- SP1 assigned to Provider A only
(@provider_a_id, @sp1_id, 'active', 1, 1, NOW(), NOW()),
-- SP2 assigned to Provider B only
(@provider_b_id, @sp2_id, 'active', 1, 1, NOW(), NOW()),
-- SP3 assigned to both providers
(@provider_a_id, @sp3_id, 'active', 0, 2, NOW(), NOW()),
(@provider_b_id, @sp3_id, 'active', 0, 2, NOW(), NOW());

-- Step 8: Activate Categories for Property Providers
INSERT INTO property_active_categories (property_provider_id, service_category_id, is_active, created_at, updated_at)
VALUES
(@provider_a_id, @plumbing_id, 1, NOW(), NOW()),
(@provider_a_id, @electrical_id, 1, NOW(), NOW()),
(@provider_b_id, @plumbing_id, 1, NOW(), NOW()),
(@provider_b_id, @electrical_id, 1, NOW(), NOW());

-- Step 9: Create Maintenance Requests
INSERT INTO maintenance_requests (uuid, request_number, property_id, service_category_id, title, description, category, priority, status, created_at, updated_at)
VALUES
-- Provider A - Plumbing requests
(UUID(), 'TEST-MR-A1', @property_a1_id, @plumbing_id, 'Test Leaking Faucet', 'Kitchen faucet is leaking', 'plumbing', 'medium', 'approved', NOW(), NOW()),
(UUID(), 'TEST-MR-A2', @property_a2_id, @plumbing_id, 'Test Clogged Drain', 'Bathroom drain is clogged', 'plumbing', 'high', 'approved', NOW(), NOW()),
-- Provider A - Electrical request
(UUID(), 'TEST-MR-A3', @property_a1_id, @electrical_id, 'Test Circuit Breaker Tripping', 'Circuit breaker keeps tripping', 'electrical', 'urgent', 'approved', NOW(), NOW()),
-- Provider B - Electrical requests
(UUID(), 'TEST-MR-B1', @property_b1_id, @electrical_id, 'Test Faulty Outlet', 'Electrical outlet not working', 'electrical', 'high', 'approved', NOW(), NOW()),
(UUID(), 'TEST-MR-B2', @property_b2_id, @electrical_id, 'Test Light Fixture Replacement', 'Replace broken light fixture', 'electrical', 'medium', 'approved', NOW(), NOW()),
-- Provider B - Plumbing request
(UUID(), 'TEST-MR-B3', @property_b1_id, @plumbing_id, 'Test Water Heater Issue', 'Water heater not heating properly', 'plumbing', 'high', 'approved', NOW(), NOW());

-- Step 10: Create Maintenance Jobs (some assigned, some available)
-- Get maintenance request IDs
SET @mr_a1_id = (SELECT id FROM maintenance_requests WHERE request_number = 'TEST-MR-A1' LIMIT 1);
SET @mr_b1_id = (SELECT id FROM maintenance_requests WHERE request_number = 'TEST-MR-B1' LIMIT 1);

INSERT INTO maintenance_jobs (uuid, job_number, maintenance_request_id, service_provider_id, status, priority, created_at, updated_at)
VALUES
(UUID(), 'TEST-MJ-001', @mr_a1_id, @sp1_id, 'assigned', 'medium', NOW(), NOW()),
(UUID(), 'TEST-MJ-002', @mr_b1_id, @sp2_id, 'assigned', 'high', NOW(), NOW());

-- Display summary
SELECT '=== TEST DATA CREATED SUCCESSFULLY ===' as message;
SELECT
    'Property Providers' as entity,
    COUNT(*) as count
FROM property_providers
WHERE company_name LIKE '%Test%'
UNION ALL
SELECT 'Properties', COUNT(*) FROM properties WHERE property_code LIKE 'TEST-%'
UNION ALL
SELECT 'Service Providers', COUNT(*) FROM service_providers WHERE provider_code LIKE 'TEST-%'
UNION ALL
SELECT 'Maintenance Requests', COUNT(*) FROM maintenance_requests WHERE request_number LIKE 'TEST-%'
UNION ALL
SELECT 'Maintenance Jobs', COUNT(*) FROM maintenance_jobs WHERE job_number LIKE 'TEST-%';

SELECT '=== TEST CREDENTIALS ===' as message;
SELECT
    'Service Provider' as type,
    email,
    'password' as password,
    'Provider A - Plumbing' as assignment
FROM users WHERE email = 'mike.test@plumbing.com'
UNION ALL
SELECT 'Service Provider', email, 'password', 'Provider B - Electrical'
FROM users WHERE email = 'sarah.test@electrical.com'
UNION ALL
SELECT 'Service Provider', email, 'password', 'Both Providers - Both Categories'
FROM users WHERE email = 'alex.test@multiskill.com';
