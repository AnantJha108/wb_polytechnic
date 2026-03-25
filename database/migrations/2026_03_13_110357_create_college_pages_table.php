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
        Schema::create('college_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('college_id')->constrained('colleges')->onDelete('cascade');
            $table->string('page'); 
            // example: about, courses, gallery
            $table->longText('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('college_pages');
    }
};
