<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technician_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();

            // Identity & Experience
            $table->string('bio')->nullable();
            $table->integer('years_experience')->default(0);
            $table->string('id_number')->nullable();
            $table->string('profile_photo')->nullable();

            // Geolocation (Core Feature)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('location_name')->nullable(); // "Kinondoni, Dar es Salaam"
            $table->integer('service_radius_km')->default(10);

            // Ratings
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->unsignedInteger('total_reviews')->default(0);

            $table->timestamps();

            // Geospatial index for location-based queries
            $table->index(['latitude', 'longitude']);
            $table->index('category_id');
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technician_profiles');
    }
};
