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
        Schema::create('news_events_notice_announcement', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('college_id');
            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('cascade');

            $table->string('title');
            $table->enum('type', ['news_events', 'notice_announcement']);
            $table->longText('description');

            $table->enum('status', ['draft', 'forwarded', 'approved', 'rejected', 'reverted'])
                ->default('draft');

            $table->text('reject_reason')->nullable();
            $table->text('revert_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_events_notice_announcement');
    }
};
