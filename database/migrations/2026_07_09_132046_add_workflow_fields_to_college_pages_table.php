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
            $table->enum('status', ['draft', 'forwarded', 'approved', 'rejected', 'reverted'])
                ->default('draft')
                ->after('college_id');

            $table->text('reject_reason')->nullable()->after('status');
            $table->text('revert_reason')->nullable()->after('reject_reason');

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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('college_pages', function (Blueprint $table) {
            //
        });
    }
};
