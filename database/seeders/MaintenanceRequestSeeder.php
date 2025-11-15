<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MaintenanceRequest;
use App\Models\Unit;
use App\Models\Tenant;

class MaintenanceRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = Unit::where('status', 'occupied')->limit(8)->get();
        $tenants = Tenant::where('status', 'active')->limit(8)->get();

        $requests = [
            [
                'title' => 'Air Conditioning Not Cooling',
                'description' => 'The AC unit in the living room is not cooling properly. Needs immediate attention.',
                'category' => 'hvac',
                'priority' => 'high',
                'status' => 'in_progress',
            ],
            [
                'title' => 'Leaking Kitchen Faucet',
                'description' => 'Kitchen sink faucet has been leaking for the past week.',
                'category' => 'plumbing',
                'priority' => 'medium',
                'status' => 'pending',
            ],
            [
                'title' => 'Broken Bedroom Window',
                'description' => 'Bedroom window glass is cracked and needs replacement.',
                'category' => 'general',
                'priority' => 'high',
                'status' => 'pending',
            ],
            [
                'title' => 'Electrical Outlet Not Working',
                'description' => 'Power outlet in the bedroom stopped working.',
                'category' => 'electrical',
                'priority' => 'medium',
                'status' => 'completed',
            ],
            [
                'title' => 'Paint Peeling in Bathroom',
                'description' => 'Bathroom walls need repainting.',
                'category' => 'general',
                'priority' => 'low',
                'status' => 'pending',
            ],
        ];

        foreach ($requests as $index => $request) {
            if (isset($units[$index]) && isset($tenants[$index])) {
                MaintenanceRequest::create(array_merge($request, [
                    'unit_id' => $units[$index]->id,
                    'tenant_id' => $tenants[$index]->id,
                    'reported_date' => now()->subDays(rand(1, 30)),
                ]));
            }
        }
    }
}
