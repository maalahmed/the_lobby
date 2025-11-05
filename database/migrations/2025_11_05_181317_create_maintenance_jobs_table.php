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
        Schema::create('maintenance_jobs', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('job_number', 50)->unique();
            
            // Relations
            $table->foreignId('maintenance_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_provider_id')->constrained()->onDelete('restrict');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Job Details
            $table->text('work_description');
            $table->json('work_items')->nullable(); // [{description, quantity, rate, amount}]
            
            // Schedule
            $table->date('scheduled_date');
            $table->time('scheduled_time_start')->nullable();
            $table->time('scheduled_time_end')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Status
            $table->enum('status', ['assigned', 'accepted', 'rejected', 'in_progress', 'completed', 'cancelled'])->default('assigned');
            
            // Cost
            $table->decimal('quoted_amount', 10, 2)->nullable();
            $table->decimal('final_amount', 10, 2)->nullable();
            $table->json('cost_breakdown')->nullable();
            
            // Quality
            $table->tinyInteger('quality_rating')->unsigned()->nullable(); // 1-5
            $table->text('quality_notes')->nullable();
            $table->json('completion_photos')->nullable();
            
            // Payment
            $table->enum('payment_status', ['pending', 'approved', 'paid'])->default('pending');
            $table->date('payment_due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('maintenance_request_id');
            $table->index('service_provider_id');
            $table->index('status');
            $table->index('scheduled_date');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_jobs');
    }
};
