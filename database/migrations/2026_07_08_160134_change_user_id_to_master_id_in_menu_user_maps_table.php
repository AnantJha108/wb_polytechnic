<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add new column
        Schema::table('menu_user_maps', function (Blueprint $table) {
            $table->unsignedBigInteger('master_id')->nullable()->after('id');
        });

        // Step 2: Migrate existing data — map each existing user_id to their role's master_id
        $maps = DB::table('menu_user_maps')->get();

        foreach ($maps as $map) {
            $user = DB::table('users')->where('id', $map->user_id)->first();

            if ($user && $user->master_id) {
                DB::table('menu_user_maps')
                    ->where('id', $map->id)
                    ->update(['master_id' => $user->master_id]);
            }
        }

        // Step 3: Drop old foreign key + column
        Schema::table('menu_user_maps', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        // Step 4: Add foreign key constraint + prevent duplicate role-menu pairs
        Schema::table('menu_user_maps', function (Blueprint $table) {
            $table->foreign('master_id')->references('id')->on('masters')->onDelete('cascade');
            $table->unique(['master_id', 'menu_id']);
        });
    }

    public function down(): void
    {
        Schema::table('menu_user_maps', function (Blueprint $table) {
            $table->dropForeign(['master_id']);
            $table->dropUnique(['master_id', 'menu_id']);
            $table->dropColumn('master_id');
        });

        Schema::table('menu_user_maps', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};