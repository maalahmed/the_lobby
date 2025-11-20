<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'uuid' => Str::uuid(),
                'name' => 'General Maintenance',
                'slug' => 'general-maintenance',
                'description' => 'General maintenance and repair services',
                'icon' => 'tools',
                'color' => '#3B82F6',
                'display_order' => 1,
                'is_active' => true,
                'requires_certification' => false,
                'requires_insurance' => false,
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Plumbing',
                'slug' => 'plumbing',
                'description' => 'Plumbing installation, repair, and maintenance services',
                'icon' => 'water',
                'color' => '#2563EB',
                'display_order' => 2,
                'is_active' => true,
                'requires_certification' => true,
                'requires_insurance' => true,
                'requirements' => [
                    'certification_types' => ['Licensed Plumber', 'Master Plumber'],
                    'insurance_minimum' => 500000,
                ],
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Electrical',
                'slug' => 'electrical',
                'description' => 'Electrical installation, repair, and maintenance services',
                'icon' => 'bolt',
                'color' => '#F59E0B',
                'display_order' => 3,
                'is_active' => true,
                'requires_certification' => true,
                'requires_insurance' => true,
                'requirements' => [
                    'certification_types' => ['Licensed Electrician', 'Master Electrician'],
                    'insurance_minimum' => 1000000,
                ],
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'HVAC',
                'slug' => 'hvac',
                'description' => 'Heating, ventilation, and air conditioning services',
                'icon' => 'wind',
                'color' => '#06B6D4',
                'display_order' => 4,
                'is_active' => true,
                'requires_certification' => true,
                'requires_insurance' => true,
                'requirements' => [
                    'certification_types' => ['HVAC Technician Certification', 'EPA 608 Certification'],
                    'insurance_minimum' => 500000,
                ],
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Carpentry',
                'slug' => 'carpentry',
                'description' => 'Carpentry and woodwork services',
                'icon' => 'hammer',
                'color' => '#92400E',
                'display_order' => 5,
                'is_active' => true,
                'requires_certification' => false,
                'requires_insurance' => true,
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Painting',
                'slug' => 'painting',
                'description' => 'Interior and exterior painting services',
                'icon' => 'brush',
                'color' => '#EC4899',
                'display_order' => 6,
                'is_active' => true,
                'requires_certification' => false,
                'requires_insurance' => true,
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Landscaping',
                'slug' => 'landscaping',
                'description' => 'Landscaping, gardening, and outdoor maintenance',
                'icon' => 'tree',
                'color' => '#10B981',
                'display_order' => 7,
                'is_active' => true,
                'requires_certification' => false,
                'requires_insurance' => true,
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Pest Control',
                'slug' => 'pest-control',
                'description' => 'Pest control and extermination services',
                'icon' => 'bug',
                'color' => '#DC2626',
                'display_order' => 8,
                'is_active' => true,
                'requires_certification' => true,
                'requires_insurance' => true,
                'requirements' => [
                    'certification_types' => ['Pesticide Applicator License'],
                    'insurance_minimum' => 500000,
                ],
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Appliance Repair',
                'slug' => 'appliance-repair',
                'description' => 'Home and commercial appliance repair services',
                'icon' => 'refrigerator',
                'color' => '#6366F1',
                'display_order' => 9,
                'is_active' => true,
                'requires_certification' => false,
                'requires_insurance' => true,
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Cleaning',
                'slug' => 'cleaning',
                'description' => 'Professional cleaning services',
                'icon' => 'sparkles',
                'color' => '#8B5CF6',
                'display_order' => 10,
                'is_active' => true,
                'requires_certification' => false,
                'requires_insurance' => true,
            ],
        ];

        foreach ($categories as $category) {
            ServiceCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('Service categories seeded successfully!');
    }
}
