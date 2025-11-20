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
        // Add service_category_id to maintenance_requests table
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->foreignId('service_category_id')
                ->after('priority')
                ->nullable() // Temporarily nullable for migration
                ->constrained()
                ->onDelete('restrict');

            $table->index('service_category_id');
        });

        // Create a default "General Maintenance" category
        $generalCategoryId = DB::table('service_categories')->insertGetId([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'name' => 'General Maintenance',
            'slug' => 'general-maintenance',
            'description' => 'General maintenance and repairs',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign all existing maintenance requests to the general category
        DB::table('maintenance_requests')
            ->whereNull('service_category_id')
            ->update(['service_category_id' => $generalCategoryId]);

        // Make service_category_id non-nullable
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->foreignId('service_category_id')
                ->nullable(false)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropForeign(['service_category_id']);
            $table->dropIndex(['service_category_id']);
            $table->dropColumn('service_category_id');
        });
    }
};
