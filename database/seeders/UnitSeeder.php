<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PropertyUnit;
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
                PropertyUnit::create([
                    'property_id' => $property->id,
                    'unit_number' => $this->generateUnitNumber($i, $property->sub_type),
                    'floor' => rand(1, 20),
                    'type' => $this->getUnitType($property->sub_type),
                    'area' => rand(80, 300), // sq meters
                    'bedrooms' => $this->getRandomBedrooms($property->sub_type),
                    'bathrooms' => rand(1, 3),
                    'rent_amount' => $this->getRentAmount($property->sub_type),
                    'rent_frequency' => 'annual',
                    'furnished' => 'unfurnished',
                    'status' => $this->getRandomStatus(),
                ]);
            }
        }
    }

    private function generateUnitNumber($index, $subType): string
    {
        if ($subType === 'villa') {
            return 'Villa-' . str_pad($index, 3, '0', STR_PAD_LEFT);
        }
        return str_pad($index, 3, '0', STR_PAD_LEFT);
    }

    private function getUnitType($subType): string
    {
        return match($subType) {
            'villa' => ['4br', '5br+'][array_rand(['4br', '5br+'])],
            'office' => 'office',
            default => ['studio', '1br', '2br', '3br'][array_rand(['studio', '1br', '2br', '3br'])],
        };
    }

    private function getRandomBedrooms($subType): int
    {
        if ($subType === 'office') {
            return 0;
        }
        if ($subType === 'villa') {
            return rand(3, 5);
        }
        return rand(1, 3);
    }

    private function getRentAmount($subType): float
    {
        return match($subType) {
            'villa' => rand(150000, 300000),
            'office' => rand(80000, 200000),
            default => rand(40000, 120000),
        };
    }

    private function getRandomStatus(): string
    {
        $statuses = ['available', 'occupied', 'occupied', 'occupied', 'maintenance'];
        return $statuses[array_rand($statuses)];
    }
}
