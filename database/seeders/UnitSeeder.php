<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\Property;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $properties = Property::all();

        foreach ($properties as $property) {
            $unitCount = min($property->total_units, 10); // Create up to 10 units per property
            
            for ($i = 1; $i <= $unitCount; $i++) {
                Unit::create([
                    'property_id' => $property->id,
                    'unit_number' => $this->generateUnitNumber($i, $property->property_type),
                    'floor' => rand(1, 20),
                    'bedrooms' => $this->getRandomBedrooms($property->property_type),
                    'bathrooms' => rand(1, 3),
                    'square_feet' => rand(800, 3000),
                    'rent_amount' => $this->getRentAmount($property->property_type),
                    'status' => $this->getRandomStatus(),
                    'description' => 'Well-maintained unit with modern amenities',
                ]);
            }
        }
    }

    private function generateUnitNumber($index, $type): string
    {
        if ($type === 'villa') {
            return 'Villa-' . str_pad($index, 3, '0', STR_PAD_LEFT);
        }
        return str_pad($index, 3, '0', STR_PAD_LEFT);
    }

    private function getRandomBedrooms($type): int
    {
        if ($type === 'commercial') {
            return 0;
        }
        if ($type === 'villa') {
            return rand(3, 5);
        }
        return rand(1, 3);
    }

    private function getRentAmount($type): float
    {
        return match($type) {
            'villa' => rand(150000, 300000),
            'commercial' => rand(80000, 200000),
            default => rand(40000, 120000),
        };
    }

    private function getRandomStatus(): string
    {
        $statuses = ['available', 'occupied', 'occupied', 'occupied', 'maintenance'];
        return $statuses[array_rand($statuses)];
    }
}
