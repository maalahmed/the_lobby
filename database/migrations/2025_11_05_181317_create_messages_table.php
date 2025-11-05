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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Parties
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            
            // Thread
            $table->foreignId('parent_id')->nullable()->constrained('messages')->onDelete('cascade'); // for threading
            $table->string('thread_id')->nullable(); // group related messages
            
            // Message Details
            $table->string('subject')->nullable();
            $table->text('body');
            $table->json('attachments')->nullable(); // [{name, path, size, type}]
            
            // Context
            $table->string('context_type')->nullable(); // property, tenant, maintenance_request, etc
            $table->unsignedBigInteger('context_id')->nullable();
            
            // Status
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_important')->default(false);
            $table->boolean('is_archived')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('sender_id');
            $table->index('recipient_id');
            $table->index('thread_id');
            $table->index(['context_type', 'context_id']);
            $table->index('read_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
