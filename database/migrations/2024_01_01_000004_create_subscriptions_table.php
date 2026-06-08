<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Plan Details
            $table->enum('plan_type', ['basic', 'standard', 'premium'])->default('basic');
            $table->decimal('amount_paid', 10, 2);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Status Machine: pending_approval -> active -> expired
            $table->enum('status', [
                'pending_approval',
                'active',
                'expired',
                'rejected',
            ])->default('pending_approval');

            // Mobile Money Verification
            $table->string('mpesa_reference')->nullable();   // M-Pesa/Tigo Pesa ref
            $table->string('payment_method')->nullable();    // mpesa, tigopesa, airtel
            $table->text('admin_notes')->nullable();
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            // Critical composite index
            $table->index(['user_id', 'status', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
