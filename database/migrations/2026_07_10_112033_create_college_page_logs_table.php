<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('college_page_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('college_page_id');
            $table->enum('action', ['forward', 'approve', 'reject', 'revert']);
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('performed_by');
            $table->string('ip_address')->nullable();
            $table->timestamps();

            $table->foreign('college_page_id')->references('id')->on('college_pages')->onDelete('cascade');
            $table->foreign('performed_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('college_page_logs');
    }
};