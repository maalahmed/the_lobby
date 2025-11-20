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
        Schema::create('service_provider_categories', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('service_provider_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_category_id')->constrained()->onDelete('cascade');

            // Provider-Specific Category Details
            $table->tinyInteger('experience_years')->unsigned()->nullable();
            $table->boolean('is_primary')->default(false); // Primary specialty
            $table->boolean('is_certified')->default(false);

            // Certifications & Qualifications
            $table->json('certifications')->nullable(); // List of certifications for this category
            $table->json('licenses')->nullable(); // Category-specific licenses
            $table->text('specialization_notes')->nullable();

            // Pricing (optional, category-specific rates)
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('minimum_charge', 10, 2)->nullable();
            $table->json('pricing_notes')->nullable();

            // Availability
            $table->json('service_hours')->nullable(); // Category-specific service hours
            $table->boolean('emergency_available')->default(false);

            // Status
            $table->boolean('is_active')->default(true);

            // Audit
            $table->timestamps();

            // Unique constraint - a provider can't have duplicate categories
            $table->unique(['service_provider_id', 'service_category_id'], 'provider_category_unique');

            // Indexes
            $table->index('service_provider_id');
            $table->index('service_category_id');
            $table->index('is_primary');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_provider_categories');
    }
};
