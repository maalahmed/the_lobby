<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\User;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing tenant users
        $tenantUsers = User::role('tenant')->get();

        $tenants = [
            [
                'user_id' => $tenantUsers[0]->id ?? null,
                'lease_start_date' => now()->subMonths(6),
                'lease_end_date' => now()->addMonths(6),
                'rent_amount' => 60000,
                'deposit_amount' => 6000,
                'status' => 'active',
            ],
            [
                'user_id' => $tenantUsers[1]->id ?? null,
                'lease_start_date' => now()->subMonths(3),
                'lease_end_date' => now()->addMonths(9),
                'rent_amount' => 75000,
                'deposit_amount' => 7500,
                'status' => 'active',
            ],
            [
                'user_id' => $tenantUsers[2]->id ?? null,
                'lease_start_date' => now()->subMonths(12),
                'lease_end_date' => now()->subMonths(1),
                'rent_amount' => 55000,
                'deposit_amount' => 5500,
                'status' => 'inactive',
            ],
        ];

        foreach ($tenants as $tenant) {
            if ($tenant['user_id']) {
                Tenant::create($tenant);
            }
        }
    }
}
