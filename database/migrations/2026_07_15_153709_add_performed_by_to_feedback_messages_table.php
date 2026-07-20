<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('feedback_messages', function (Blueprint $table) {
            $table->unsignedBigInteger('performed_by')->nullable()->after('sender');
        });
    }

    public function down(): void
    {
        Schema::table('feedback_messages', function (Blueprint $table) {
            $table->dropColumn('performed_by');
        });
    }
};