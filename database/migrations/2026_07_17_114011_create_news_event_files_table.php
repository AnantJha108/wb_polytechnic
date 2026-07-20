<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_event_files', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('news_event_id');
            $table->foreign('news_event_id')
                  ->references('id')
                  ->on('news_events_notice_announcement')   // ← updated to match your real table
                  ->onDelete('cascade');

            $table->string('original_name');
            $table->string('mime_type');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_event_files');
    }
};