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
        Schema::create('service_providers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('provider_code', 50)->unique();
            
            // Company
            $table->string('company_name');
            $table->string('business_registration', 100)->nullable();
            $table->string('tax_number', 100)->nullable();
            
            // Contact
            $table->string('primary_contact_name')->nullable();
            $table->string('primary_contact_phone', 20)->nullable();
            $table->string('primary_contact_email')->nullable();
            $table->json('office_address')->nullable();
            
            // Services
            $table->json('service_categories')->nullable();
            $table->json('specializations')->nullable();
            $table->json('service_areas')->nullable();
            
            // Business
            $table->tinyInteger('years_in_business')->unsigned()->nullable();
            $table->smallInteger('team_size')->unsigned()->nullable();
            $table->json('working_hours')->nullable();
            $table->boolean('emergency_services')->default(false);
            
            // Certifications
            $table->json('licenses')->nullable();
            $table->json('insurance_details')->nullable();
            $table->json('certifications')->nullable();
            
            // Financial
            $table->string('payment_terms')->nullable();
            $table->json('bank_details')->nullable();
            $table->json('tax_information')->nullable();
            
            // Performance
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->smallInteger('total_jobs')->unsigned()->default(0);
            $table->smallInteger('completed_jobs')->unsigned()->default(0);
            $table->smallInteger('cancelled_jobs')->unsigned()->default(0);
            
            // Status
            $table->enum('status', ['active', 'inactive', 'suspended', 'blacklisted'])->default('active');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->index('provider_code');
            $table->index('status');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_providers');
    }
};
