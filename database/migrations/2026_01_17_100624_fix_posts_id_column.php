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

        // Check if id column already has auto_increment and primary key
        // If primary key already exists, we just need to ensure the column is properly configured
        $columnInfo = DB::select("SHOW COLUMNS FROM posts WHERE Field = 'id'");

        if (!empty($columnInfo)) {
            $column = $columnInfo[0];

            // Check if already has auto_increment
            if (stripos($column->Extra ?? '', 'auto_increment') === false) {
                // Only modify without PRIMARY KEY since it already exists
                DB::statement('ALTER TABLE posts MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            }
            // If already has auto_increment, no changes needed
        }
    }

    public function down(): void
    {
        // Reverting this is tricky and generally not needed as we are fixing a broken state.
        // But for completeness, we could drop the primary key, though we can't easily revert auto_increment to exactly what it was (null).
    }
};
