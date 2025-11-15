<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeaseContract;
use App\Models\Unit;
use App\Models\Tenant;

class LeaseContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = Unit::where('status', 'occupied')->limit(10)->get();
        $tenants = Tenant::where('status', 'active')->get();

        foreach ($tenants as $index => $tenant) {
            if (isset($units[$index])) {
                LeaseContract::create([
                    'unit_id' => $units[$index]->id,
                    'tenant_id' => $tenant->id,
                    'start_date' => $tenant->lease_start_date,
                    'end_date' => $tenant->lease_end_date,
                    'rent_amount' => $tenant->rent_amount,
                    'deposit_amount' => $tenant->deposit_amount,
                    'payment_frequency' => 'monthly',
                    'payment_day' => 1,
                    'contract_terms' => 'Standard residential lease agreement terms and conditions apply.',
                    'status' => 'active',
                    'signed_date' => $tenant->lease_start_date,
                ]);
            }
        }
    }
}
