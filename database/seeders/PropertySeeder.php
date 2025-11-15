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
        // Get landlord user, or fallback to any user with landlord email
        $landlord = User::role('landlord')->first();
        
        if (!$landlord) {
            $landlord = User::where('email', 'landlord@thelobby.com')->first();
        }
        
        if (!$landlord) {
            $this->command->error('No landlord user found. Please run UserSeeder first.');
            return;
        }

        $properties = [
            [
                'name' => 'Palm Residences Tower A',
                'description' => 'Luxury residential tower located in Palm Jumeirah with stunning sea views',
                'address_line_1' => 'Palm Jumeirah',
                'address_line_2' => null,
                'city' => 'Dubai',
                'state' => 'Dubai',
                'postal_code' => '00000',
                'country' => 'UAE',
                'owner_id' => $landlord->id,
                'type' => 'residential',
                'sub_type' => 'apartment',
                'total_units' => 50,
                'status' => 'active',
            ],
            [
                'name' => 'Marina Bay Complex',
                'description' => 'Modern residential complex in Dubai Marina with amenities',
                'address_line_1' => 'Dubai Marina',
                'address_line_2' => null,
                'city' => 'Dubai',
                'state' => 'Dubai',
                'postal_code' => '00000',
                'country' => 'UAE',
                'owner_id' => $landlord->id,
                'type' => 'residential',
                'sub_type' => 'apartment',
                'total_units' => 30,
                'status' => 'active',
            ],
            [
                'name' => 'Business Park Plaza',
                'description' => 'Commercial office space in Business Bay',
                'address_line_1' => 'Business Bay',
                'address_line_2' => null,
                'city' => 'Dubai',
                'state' => 'Dubai',
                'postal_code' => '00000',
                'country' => 'UAE',
                'owner_id' => $landlord->id,
                'type' => 'commercial',
                'sub_type' => 'office',
                'total_units' => 20,
                'status' => 'active',
            ],
            [
                'name' => 'Green Valley Villas',
                'description' => 'Spacious villa community in Arabian Ranches',
                'address_line_1' => 'Arabian Ranches',
                'address_line_2' => null,
                'city' => 'Dubai',
                'state' => 'Dubai',
                'postal_code' => '00000',
                'country' => 'UAE',
                'owner_id' => $landlord->id,
                'type' => 'residential',
                'sub_type' => 'villa',
                'total_units' => 15,
                'status' => 'active',
            ],
            [
                'name' => 'Downtown Heights',
                'description' => 'High-rise building in Downtown Dubai',
                'address_line_1' => 'Downtown Dubai',
                'address_line_2' => null,
                'city' => 'Dubai',
                'state' => 'Dubai',
                'postal_code' => '00000',
                'country' => 'UAE',
                'owner_id' => $landlord->id,
                'type' => 'residential',
                'sub_type' => 'apartment',
                'total_units' => 40,
                'status' => 'active',
            ],
        ];

        foreach ($properties as $property) {
            Property::create($property);
        }
    }
}
