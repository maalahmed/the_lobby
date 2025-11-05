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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Notification Details
            $table->string('type'); // invoice_due, payment_received, maintenance_scheduled, etc
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // additional contextual data
            
            // Related Entity
            $table->string('notifiable_type')->nullable(); // polymorphic
            $table->unsignedBigInteger('notifiable_id')->nullable();
            
            // Channels
            $table->json('channels')->nullable(); // ['database', 'mail', 'sms', 'push']
            
            // Status
            $table->timestamp('read_at')->nullable();
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->boolean('is_actionable')->default(false);
            $table->string('action_url', 500)->nullable();
            
            // Delivery
            $table->timestamp('sent_at')->nullable();
            $table->json('delivery_status')->nullable(); // per channel
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->index(['notifiable_type', 'notifiable_id']);
            $table->index('type');
            $table->index('read_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
