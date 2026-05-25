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
        Schema::table('users', function (Blueprint $table) {
            // Wrong OTP Attempts
            $table->integer('otp_attempts')->default(0);

            // Lock resend OTP till
            $table->timestamp('otp_resend_locked_until')->nullable();

            // Real lock start time
            $table->timestamp('otp_lock_started_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
