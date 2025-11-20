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
        // Add user_type column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['tenant', 'service_provider', 'landlord', 'admin'])
                ->after('email')
                ->nullable(); // Temporarily nullable for migration
        });

        // Set user_type based on existing roles
        DB::statement("
            UPDATE users u
            LEFT JOIN model_has_roles mhr ON u.id = mhr.model_id AND mhr.model_type = 'App\\\\Models\\\\User'
            LEFT JOIN roles r ON mhr.role_id = r.id
            SET u.user_type = CASE
                WHEN r.name = 'tenant' THEN 'tenant'
                WHEN r.name = 'service_provider' THEN 'service_provider'
                WHEN r.name = 'landlord' THEN 'landlord'
                WHEN r.name = 'admin' THEN 'admin'
                ELSE 'tenant'
            END
        ");

        // Make user_type non-nullable after data migration
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['tenant', 'service_provider', 'landlord', 'admin'])
                ->nullable(false)
                ->default('tenant')
                ->change();
        });

        // Add index for better query performance
        Schema::table('users', function (Blueprint $table) {
            $table->index('user_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['user_type']);
            $table->dropColumn('user_type');
        });
    }
};
