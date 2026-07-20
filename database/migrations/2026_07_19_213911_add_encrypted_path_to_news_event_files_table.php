<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_event_files', function (Blueprint $table) {
            $table->string('encrypted_path')->nullable()->after('mime_type');
        });
    }

    public function down(): void
    {
        Schema::table('news_event_files', function (Blueprint $table) {
            $table->dropColumn('encrypted_path');
        });
    }
};