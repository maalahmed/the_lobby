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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('profile_type', ['admin', 'landlord', 'tenant', 'service_provider']);
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('national_id', 50)->nullable();
            $table->string('passport_number', 50)->nullable();
            $table->json('address')->nullable(); // {street, city, state, postal_code, country, coordinates}
            $table->json('emergency_contact')->nullable(); // {name, phone, relationship, address}
            $table->json('preferences')->nullable(); // {language, timezone, notifications}
            $table->json('documents')->nullable(); // {type, url, verified_at, expires_at}
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('profile_type');
            $table->index('national_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
