<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technician_works', function (Blueprint $blueprint) {
            $blueprint->id();
            // Inamfunga mfanyakazi na table ya users (foreign key)
            $blueprint->foreignId('user_id')->constrained()->cascadeOnDelete();
            $blueprint->string('title');
            $blueprint->text('description')->nullable();
            $blueprint->string('image_path'); // Sehemu ya kuhifadhi jina/njia ya picha
            $blueprint->boolean('is_visible')->default(true);
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technician_works');
    }
};