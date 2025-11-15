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
        $tenants = Tenant::where('status', 'active')->get();
        $landlord = User::role('landlord')->first();

        if ($landlord && $tenants->count() > 0 && $occupiedUnits->count() > 0) {
            foreach ($tenants as $index => $tenant) {
                if (isset($occupiedUnits[$index])) {
                    LeaseContract::create([
                        'property_id' => $occupiedUnits[$index]->property_id,
                        'unit_id' => $occupiedUnits[$index]->id,
                        'tenant_id' => $tenant->id,
                        'landlord_id' => $landlord->id,
                        'start_date' => now()->subMonths(rand(3, 10)),
                        'end_date' => now()->addMonths(rand(2, 14)),
                        'signed_date' => now()->subDays(rand(30, 60)),
                        'rent_amount' => $occupiedUnits[$index]->rent_amount,
                        'security_deposit' => $occupiedUnits[$index]->rent_amount * 0.1,
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
