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
        Schema::create('property_amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('name', 100);
            $table->string('category', 50); // fitness, security, entertainment, utilities
            $table->text('description')->nullable();
            $table->boolean('available')->default(true);
            $table->json('schedule')->nullable(); // operating hours
            $table->decimal('cost', 8, 2)->nullable(); // if chargeable
            $table->timestamps();
            
            $table->index('property_id');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_amenities');
    }
};
