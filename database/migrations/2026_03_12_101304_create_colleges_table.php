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
        Schema::create('colleges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('college_id')->nullable();
            $table->longText('logo')->nullable();
            $table->string('contact_no',20);
            $table->string('email')->unique();
            $table->text('address');
            $table->tinyInteger('status')->default(1);
            $table->foreignId('template_id')->constrained('templates')->onDelete('cascade');
            $table->string('district')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colleges');
    }
    
};
