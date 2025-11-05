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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tenant_code', 50)->unique();
            
            // Personal Information
            $table->string('occupation')->nullable();
            $table->string('employer')->nullable();
            $table->decimal('monthly_income', 10, 2)->nullable();
            
            // Emergency Contact & References
            $table->json('emergency_contact')->nullable();
            $table->json('references')->nullable();
            $table->json('documents')->nullable();
            $table->json('previous_addresses')->nullable();
            
            // Status
            $table->enum('status', ['active', 'inactive', 'blacklisted', 'prospect'])->default('prospect');
            $table->integer('credit_score')->nullable();
            $table->enum('background_check_status', ['pending', 'passed', 'failed', 'not_required'])->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->index('tenant_code');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
