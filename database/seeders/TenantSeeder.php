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

        foreach ($tenantUsers as $user) {
            Tenant::create([
                'user_id' => $user->id,
                'occupation' => ['Software Engineer', 'Teacher', 'Business Owner'][array_rand(['Software Engineer', 'Teacher', 'Business Owner'])],
                'employer' => ['Tech Company', 'School', 'Self Employed'][array_rand(['Tech Company', 'School', 'Self Employed'])],
                'monthly_income' => rand(15000, 50000),
                'emergency_contact' => json_encode([
                    'name' => 'Emergency Contact',
                    'phone' => '+97150' . rand(1000000, 9999999),
                    'relationship' => 'Family',
                ]),
                'status' => 'active',
                'credit_score' => rand(650, 850),
                'background_check_status' => 'passed',
            ]);
        }
    }
}
