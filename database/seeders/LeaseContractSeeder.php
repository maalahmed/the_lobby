<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeaseContract;
use App\Models\PropertyUnit;
use App\Models\Tenant;
use App\Models\User;

class LeaseContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $occupiedUnits = PropertyUnit::where('status', 'occupied')->get();
        $activeTenants = Tenant::where('status', 'active')->get();
        $landlord = User::role('landlord')->first();

        if ($landlord && $activeTenants->count() > 0 && $occupiedUnits->count() > 0) {
            foreach ($activeTenants as $index => $tenant) {
                if (isset($occupiedUnits[$index])) {
                    LeaseContract::create([
                        'property_id' => $occupiedUnits[$index]->property_id,
                        'unit_id' => $occupiedUnits[$index]->id,
                        'tenant_id' => $tenant->id,
                        'landlord_id' => $landlord->id,
                        'start_date' => $tenant->lease_start_date,
                        'end_date' => $tenant->lease_end_date,
                        'signed_date' => now()->subDays(rand(30, 60)),
                        'rent_amount' => $tenant->rent_amount,
                        'security_deposit' => $tenant->security_deposit,
                        'rent_frequency' => 'annual',
                        'payment_due_day' => 1,
                        'terms_conditions' => 'Standard lease agreement terms and conditions apply.',
                        'status' => 'active',
                    ]);
                }
            }
        }
    }
}
