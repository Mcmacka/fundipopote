<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique(); // e.g., FP-2024-AB12CD

            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();

            // Job Details
            $table->text('description');
            $table->string('location_address');
            $table->decimal('location_lat', 10, 8)->nullable();
            $table->decimal('location_lng', 11, 8)->nullable();
            $table->timestamp('scheduled_at')->nullable();

            // Status Flow
            $table->enum('status', [
                'pending',      // Customer submitted
                'accepted',     // Technician accepted
                'rejected',     // Technician rejected
                'in_progress',  // Job started
                'completed',    // Job done
                'cancelled',    // Either party cancelled
            ])->default('pending');

            $table->decimal('agreed_price', 10, 2)->nullable();
            $table->text('technician_notes')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id', 'status']);
            $table->index(['technician_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
