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
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Category Information
            $table->string('name'); // e.g., "Plumbing", "Electrical", "HVAC"
            $table->string('slug')->unique(); // e.g., "plumbing", "electrical"
            $table->text('description')->nullable();

            // Visual
            $table->string('icon')->nullable(); // Icon name or path
            $table->string('color', 7)->default('#3B82F6'); // Hex color code

            // Organization
            $table->foreignId('parent_id')->nullable()->constrained('service_categories')->onDelete('cascade');
            $table->smallInteger('display_order')->default(0);

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_certification')->default(false);
            $table->boolean('requires_insurance')->default(false);

            // Metadata
            $table->json('requirements')->nullable(); // Specific requirements for this category
            $table->json('metadata')->nullable(); // Additional data

            // Audit
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('slug');
            $table->index('is_active');
            $table->index('parent_id');
            $table->index('display_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_categories');
    }
};
