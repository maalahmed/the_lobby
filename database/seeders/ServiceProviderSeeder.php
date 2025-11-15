<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceProvider;
use App\Models\User;

class ServiceProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get service provider users
        $spUsers = User::role('service_provider')->get();

        $providers = [
            [
                'user_id' => $spUsers[0]->id ?? null,
                'company_name' => 'Dubai AC Services',
                'business_registration' => 'LIC-HVAC-2024-001',
                'service_categories' => json_encode(['HVAC', 'Air Conditioning']),
                'specializations' => json_encode(['AC Repair', 'AC Installation', 'AC Maintenance']),
                'years_in_business' => 10,
                'team_size' => 15,
                'emergency_services' => true,
                'status' => 'active',
            ],
            [
                'user_id' => $spUsers[1]->id ?? null,
                'company_name' => 'Elite Plumbing Solutions',
                'business_registration' => 'LIC-PLUMB-2024-002',
                'service_categories' => json_encode(['Plumbing', 'Water Systems']),
                'specializations' => json_encode(['Pipe Repair', 'Leak Detection', 'Water Heater']),
                'years_in_business' => 8,
                'team_size' => 12,
                'emergency_services' => true,
                'status' => 'active',
            ],
            [
                'user_id' => $spUsers[2]->id ?? null,
                'company_name' => 'Power Electrical Works',
                'business_registration' => 'LIC-ELEC-2024-003',
                'service_categories' => json_encode(['Electrical', 'Power Systems']),
                'specializations' => json_encode(['Wiring', 'Circuit Repair', 'Lighting']),
                'years_in_business' => 12,
                'team_size' => 20,
                'emergency_services' => true,
                'status' => 'active',
            ],
        ];

        foreach ($providers as $provider) {
            if ($provider['user_id']) {
                ServiceProvider::create($provider);
            }
        }
    }
}
