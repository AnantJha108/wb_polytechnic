<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_page_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('about_page_id');
            $table->foreign('about_page_id')->references('id')->on('about_pages')->onDelete('cascade');

            $table->string('action'); // forward, approve, reject, revert
            $table->text('reason')->nullable();

            $table->unsignedBigInteger('performed_by')->nullable();
            $table->string('ip_address')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_page_logs');
    }
};