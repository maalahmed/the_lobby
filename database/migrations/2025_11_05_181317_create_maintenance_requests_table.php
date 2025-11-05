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
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('request_number', 50)->unique();
            
            // Location
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained('property_units')->onDelete('cascade');
            
            // Request Details
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['plumbing', 'electrical', 'hvac', 'appliance', 'structural', 'pest_control', 'cleaning', 'landscaping', 'security', 'other']);
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            
            // People
            $table->foreignId('requested_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('tenant_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            
            // Status
            $table->enum('status', ['pending', 'approved', 'assigned', 'in_progress', 'on_hold', 'completed', 'cancelled'])->default('pending');
            
            // Scheduling
            $table->date('preferred_date')->nullable();
            $table->time('preferred_time_start')->nullable();
            $table->time('preferred_time_end')->nullable();
            $table->date('scheduled_date')->nullable();
            $table->time('scheduled_time_start')->nullable();
            $table->time('scheduled_time_end')->nullable();
            
            // Access
            $table->text('access_instructions')->nullable();
            $table->boolean('tenant_present_required')->default(false);
            $table->boolean('keys_required')->default(false);
            
            // Cost
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->decimal('approved_cost', 10, 2)->nullable();
            $table->decimal('final_cost', 10, 2)->nullable();
            $table->boolean('cost_approval_required')->default(false);
            $table->foreignId('cost_approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('cost_approved_at')->nullable();
            
            // Completion
            $table->timestamp('completed_at')->nullable();
            $table->text('completion_notes')->nullable();
            $table->tinyInteger('tenant_satisfaction_rating')->unsigned()->nullable();
            $table->text('tenant_feedback')->nullable();
            
            // Media
            $table->json('initial_photos')->nullable();
            $table->json('completion_photos')->nullable();
            
            // Recurring
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurring_frequency', ['weekly', 'monthly', 'quarterly', 'semi_annual', 'annual'])->nullable();
            $table->date('next_due_date')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('property_id');
            $table->index('unit_id');
            $table->index('requested_by');
            $table->index('assigned_to');
            $table->index('status');
            $table->index('priority');
            $table->index('category');
            $table->index('scheduled_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
