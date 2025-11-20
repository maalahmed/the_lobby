<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add property_provider_id to properties table
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('property_provider_id')
                ->after('id')
                ->nullable() // Temporarily nullable for migration
                ->constrained()
                ->onDelete('restrict');
        });

        // Create a default property provider for existing data
        $defaultProviderId = DB::table('property_providers')->insertGetId([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'company_name' => 'Default Property Management',
            'contact_name' => 'System Admin',
            'contact_email' => 'admin@thelobby.com',
            'contact_phone' => '000-000-0000',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign all existing properties to the default provider
        DB::table('properties')
            ->whereNull('property_provider_id')
            ->update(['property_provider_id' => $defaultProviderId]);

        // Make property_provider_id non-nullable
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('property_provider_id')
                ->nullable(false)
                ->change();
        });

        // Add index
        Schema::table('properties', function (Blueprint $table) {
            $table->index('property_provider_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['property_provider_id']);
            $table->dropIndex(['property_provider_id']);
            $table->dropColumn('property_provider_id');
        });
    }
};
