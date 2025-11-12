<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            // Make requested_by nullable and change onDelete to set null
            $table->foreignId('requested_by')->nullable()->change();
        });
        
        // Update the foreign key constraint
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropForeign(['requested_by']);
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            // Revert back to original constraint
            $table->dropForeign(['requested_by']);
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('restrict');
        });
        
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->foreignId('requested_by')->nullable(false)->change();
        });
    }
};
