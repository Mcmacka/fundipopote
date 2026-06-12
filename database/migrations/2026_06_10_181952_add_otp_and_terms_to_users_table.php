<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('otp_code')->nullable();
        $table->timestamp('otp_expires_at')->nullable();
        $table->boolean('is_verified')->default(false);
        $table->boolean('terms_accepted')->default(false);
    });
}

// Usisahau pia kuweka 'down' ili uweze kurudi nyuma kama ukikosea
public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['otp_code', 'otp_expires_at', 'is_verified', 'terms_accepted']);
    });
}
};
