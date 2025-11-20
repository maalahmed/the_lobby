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
        Schema::create('property_provider_service_providers', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('property_provider_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_provider_id')->constrained()->onDelete('cascade');

            // Relationship Status
            $table->enum('status', ['active', 'inactive', 'suspended', 'blacklisted'])->default('active');
            $table->boolean('is_preferred')->default(false); // Preferred provider
            $table->smallInteger('priority')->default(0); // 0 = highest priority

            // Financial
            $table->decimal('rate_multiplier', 5, 2)->default(1.00); // Apply to provider's base rates
            $table->string('payment_terms')->nullable();
            $table->json('custom_rates')->nullable(); // Category-specific custom rates

            // Performance Tracking
            $table->smallInteger('jobs_assigned')->unsigned()->default(0);
            $table->smallInteger('jobs_completed')->unsigned()->default(0);
            $table->smallInteger('jobs_cancelled')->unsigned()->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0.00);
            $table->decimal('avg_completion_time', 8, 2)->nullable(); // Hours

            // Restrictions & Notes
            $table->json('restrictions')->nullable(); // e.g., specific properties, time restrictions
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable(); // Internal notes

            // Audit
            $table->timestamp('relationship_started_at')->nullable();
            $table->timestamp('relationship_ended_at')->nullable();
            $table->timestamps();

            // Unique constraint
            $table->unique(['property_provider_id', 'service_provider_id'], 'property_service_provider_unique');

            // Indexes
            $table->index('property_provider_id');
            $table->index('service_provider_id');
            $table->index('status');
            $table->index('is_preferred');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_provider_service_providers');
    }
};
