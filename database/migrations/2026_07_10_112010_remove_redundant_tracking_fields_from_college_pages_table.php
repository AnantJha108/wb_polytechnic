<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('college_pages', function (Blueprint $table) {
            $table->dropColumn([
                'forwarded_at', 'forwarded_ip',
                'approved_at', 'approved_ip',
                'rejected_at', 'rejected_ip',
                'reverted_at', 'reverted_ip',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('college_pages', function (Blueprint $table) {
            $table->timestamp('forwarded_at')->nullable();
            $table->string('forwarded_ip')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('approved_ip')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->string('rejected_ip')->nullable();
            $table->timestamp('reverted_at')->nullable();
            $table->string('reverted_ip')->nullable();
        });
    }
};