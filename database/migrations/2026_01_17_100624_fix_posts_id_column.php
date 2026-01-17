<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete any posts with null ID (data cleanup)
        DB::table('posts')->whereNull('id')->delete();

        // Alter the table to make id Primary Key and Auto Increment
        // We use DB::statement because modifying to Primary Key + Auto Increment is complex with Schema builder if column exists
        DB::statement('ALTER TABLE posts MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY');
    }

    public function down(): void
    {
        // Reverting this is tricky and generally not needed as we are fixing a broken state.
        // But for completeness, we could drop the primary key, though we can't easily revert auto_increment to exactly what it was (null).
    }
};
