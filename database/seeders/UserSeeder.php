<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@thelobby.com',
            'password' => Hash::make('password'),
            'phone' => '+97150123456',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');
        UserProfile::create([
            'user_id' => $admin->id,
            'profile_type' => 'admin',
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'preferred_language' => 'en',
            'preferred_currency' => 'AED',
            'timezone' => 'Asia/Dubai',
        ]);

        // Landlord User
        $landlord = User::create([
            'name' => 'Ahmed Al-Mansouri',
            'email' => 'landlord@thelobby.com',
            'password' => Hash::make('password'),
            'phone' => '+97150234567',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $landlord->assignRole('landlord');
        UserProfile::create([
            'user_id' => $landlord->id,
            'profile_type' => 'landlord',
            'first_name' => 'Ahmed',
            'last_name' => 'Al-Mansouri',
            'preferred_language' => 'ar',
            'preferred_currency' => 'AED',
            'timezone' => 'Asia/Dubai',
            'city' => 'Dubai',
            'country' => 'UAE',
        ]);

        // Tenant User
        $tenant = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'tenant@thelobby.com',
            'password' => Hash::make('password'),
            'phone' => '+97150345678',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $tenant->assignRole('tenant');
        UserProfile::create([
            'user_id' => $tenant->id,
            'profile_type' => 'tenant',
            'first_name' => 'Sarah',
            'last_name' => 'Johnson',
            'preferred_language' => 'en',
            'preferred_currency' => 'AED',
            'timezone' => 'Asia/Dubai',
        ]);

        // Service Provider User
        $provider = User::create([
            'name' => 'Quick Fix Maintenance',
            'email' => 'provider@thelobby.com',
            'password' => Hash::make('password'),
            'phone' => '+97150456789',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $provider->assignRole('service_provider');
        UserProfile::create([
            'user_id' => $provider->id,
            'profile_type' => 'service_provider',
            'first_name' => 'Quick Fix',
            'last_name' => 'Maintenance',
            'preferred_language' => 'en',
            'preferred_currency' => 'AED',
            'timezone' => 'Asia/Dubai',
        ]);
    }
}
