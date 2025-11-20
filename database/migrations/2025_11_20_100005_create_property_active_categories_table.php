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
        Schema::create('property_active_categories', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('property_provider_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_category_id')->constrained()->onDelete('cascade');

            // Activation Details
            $table->boolean('is_active')->default(true);
            $table->text('activation_notes')->nullable();

            // Category-Specific Settings for this Property Provider
            $table->boolean('auto_assign')->default(false); // Auto-assign to preferred providers
            $table->boolean('requires_approval')->default(true); // Require approval before job assignment
            $table->smallInteger('max_concurrent_jobs')->nullable(); // Limit per provider

            // SLA & Response Times
            $table->integer('max_response_time_minutes')->nullable(); // Expected provider response time
            $table->integer('max_completion_time_hours')->nullable(); // Expected completion time

            // Pricing Limits
            $table->decimal('min_quote_amount', 10, 2)->nullable();
            $table->decimal('max_quote_amount', 10, 2)->nullable();
            $table->boolean('requires_multiple_quotes')->default(false);
            $table->tinyInteger('min_quotes_required')->default(1);

            // Notifications
            $table->json('notification_settings')->nullable(); // Category-specific notifications

            // Audit
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->foreignId('activated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Unique constraint
            $table->unique(['property_provider_id', 'service_category_id'], 'property_category_unique');

            // Indexes
            $table->index('property_provider_id');
            $table->index('service_category_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_active_categories');
    }
};
