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
                'service_type' => 'HVAC',
                'license_number' => 'LIC-HVAC-2024-001',
                'rating' => 4.5,
                'status' => 'active',
            ],
            [
                'user_id' => $spUsers[1]->id ?? null,
                'company_name' => 'Elite Plumbing Solutions',
                'service_type' => 'Plumbing',
                'license_number' => 'LIC-PLUMB-2024-002',
                'rating' => 4.8,
                'status' => 'active',
            ],
            [
                'user_id' => $spUsers[2]->id ?? null,
                'company_name' => 'Power Electrical Works',
                'service_type' => 'Electrical',
                'license_number' => 'LIC-ELEC-2024-003',
                'rating' => 4.3,
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
