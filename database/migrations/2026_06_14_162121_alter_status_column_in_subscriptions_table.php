<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    // Kama unatumia ENUM:
    DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('pending_approval', 'active', 'rejected', 'expired', 'queued') DEFAULT 'pending_approval'");
    
    // AU kama column ni VARCHAR (ambayo ni bora zaidi):
    // DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status VARCHAR(20)");
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            //
        });
    }
};
