<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $landlord = User::role('landlord')->first();

        $properties = [
            [
                'name' => 'Palm Residences Tower A',
                'description' => 'Luxury residential tower located in Palm Jumeirah with stunning sea views',
                'address' => 'Palm Jumeirah, Dubai',
                'city' => 'Dubai',
                'state' => 'Dubai',
                'zip_code' => '00000',
                'country' => 'UAE',
                'landlord_id' => $landlord->id,
                'property_type' => 'apartment',
                'total_units' => 50,
                'status' => 'active',
            ],
            [
                'name' => 'Marina Bay Complex',
                'description' => 'Modern residential complex in Dubai Marina with amenities',
                'address' => 'Dubai Marina, Dubai',
                'city' => 'Dubai',
                'state' => 'Dubai',
                'zip_code' => '00000',
                'country' => 'UAE',
                'landlord_id' => $landlord->id,
                'property_type' => 'apartment',
                'total_units' => 30,
                'status' => 'active',
            ],
            [
                'name' => 'Business Park Plaza',
                'description' => 'Commercial office space in Business Bay',
                'address' => 'Business Bay, Dubai',
                'city' => 'Dubai',
                'state' => 'Dubai',
                'zip_code' => '00000',
                'country' => 'UAE',
                'landlord_id' => $landlord->id,
                'property_type' => 'commercial',
                'total_units' => 20,
                'status' => 'active',
            ],
            [
                'name' => 'Green Valley Villas',
                'description' => 'Spacious villa community in Arabian Ranches',
                'address' => 'Arabian Ranches, Dubai',
                'city' => 'Dubai',
                'state' => 'Dubai',
                'zip_code' => '00000',
                'country' => 'UAE',
                'landlord_id' => $landlord->id,
                'property_type' => 'villa',
                'total_units' => 15,
                'status' => 'active',
            ],
            [
                'name' => 'Downtown Heights',
                'description' => 'High-rise building in Downtown Dubai',
                'address' => 'Downtown Dubai, Dubai',
                'city' => 'Dubai',
                'state' => 'Dubai',
                'zip_code' => '00000',
                'country' => 'UAE',
                'landlord_id' => $landlord->id,
                'property_type' => 'apartment',
                'total_units' => 40,
                'status' => 'active',
            ],
        ];

        foreach ($properties as $property) {
            Property::create($property);
        }
    }
}
