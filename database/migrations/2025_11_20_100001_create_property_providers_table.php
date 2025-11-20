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
        Schema::create('property_providers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Company Information
            $table->string('company_name');
            $table->string('business_registration', 100)->nullable();
            $table->string('tax_number', 100)->nullable();
            $table->string('website')->nullable();

            // Primary Contact
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone', 20);

            // Address
            $table->json('address')->nullable(); // {street, city, state, zip, country}
            $table->json('billing_address')->nullable();

            // Business Details
            $table->text('description')->nullable();
            $table->json('service_areas')->nullable(); // Geographic areas served
            $table->smallInteger('properties_count')->unsigned()->default(0);
            $table->smallInteger('units_count')->unsigned()->default(0);

            // Subscription & Billing
            $table->enum('subscription_tier', ['basic', 'professional', 'enterprise'])->default('basic');
            $table->date('subscription_expires_at')->nullable();
            $table->json('billing_details')->nullable();

            // Settings
            $table->json('settings')->nullable(); // Custom settings per property provider
            $table->string('timezone')->default('UTC');
            $table->string('currency')->default('USD');
            $table->string('language')->default('en');

            // Status
            $table->enum('status', ['active', 'inactive', 'suspended', 'trial'])->default('active');
            $table->text('notes')->nullable();

            // Audit
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('company_name');
            $table->index('status');
            $table->index('subscription_tier');
            $table->index('subscription_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_providers');
    }
};
