<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('technician_profiles', function (Blueprint $table) {
        $table->string('certificate_path')->nullable();
        $table->string('residency_letter_path')->nullable();
        // Kama huna column ya status, unaweza kuiongeza pia
        if (!Schema::hasColumn('technician_profiles', 'status')) {
            $table->string('status')->default('pending');
        }
    });
}

public function down(): void
{
    Schema::table('technician_profiles', function (Blueprint $table) {
        $table->dropColumn(['certificate_path', 'residency_letter_path', 'status']);
    });
}
};
