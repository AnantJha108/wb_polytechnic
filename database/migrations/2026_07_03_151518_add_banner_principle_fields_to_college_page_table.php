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
        Schema::table('college_pages', function (Blueprint $table) {
            $table->longText('banner')->nullable();
            $table->longText('principle_image')->nullable();
            $table->text('principle_message')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collegePage', function (Blueprint $table) {
            //
        });
    }
};
