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
        Schema::table('posts', function (Blueprint $table) {
            // Add indexes for performance optimization (only if they don't exist)
            if (!$this->indexExists('posts', 'posts_created_at_index')) {
                $table->index(['created_at']); // For sorting by date
            }
            if (!$this->indexExists('posts', 'posts_likes_count_created_at_index')) {
                $table->index(['likes_count', 'created_at']); // For weighted feed algorithm
            }
            if (!$this->indexExists('posts', 'posts_media_type_created_at_index')) {
                $table->index(['media_type', 'created_at']); // For filtering by media type
            }
            // Note: posts_user_id_created_at_index already exists from original migration
        });

        Schema::table('post_likes', function (Blueprint $table) {
            // Add indexes for likes table
            if (!$this->indexExists('post_likes', 'post_likes_user_id_post_id_index')) {
                $table->index(['user_id', 'post_id']); // For checking if user liked post
            }
            if (!$this->indexExists('post_likes', 'post_likes_post_id_created_at_index')) {
                $table->index(['post_id', 'created_at']); // For post likes timeline
            }
        });

        Schema::table('comments', function (Blueprint $table) {
            // Add indexes for comments table
            if (!$this->indexExists('comments', 'comments_post_id_created_at_index')) {
                $table->index(['post_id', 'created_at']); // For post comments timeline
            }
            if (!$this->indexExists('comments', 'comments_user_id_created_at_index')) {
                $table->index(['user_id', 'created_at']); // For user comments timeline
            }
        });

        Schema::table('follows', function (Blueprint $table) {
            // Add indexes for follows table
            if (!$this->indexExists('follows', 'follows_follower_id_created_at_index')) {
                $table->index(['follower_id', 'created_at']); // For user following timeline
            }
            if (!$this->indexExists('follows', 'follows_following_id_created_at_index')) {
                $table->index(['following_id', 'created_at']); // For user followers timeline
            }
        });

        Schema::table('saved_posts', function (Blueprint $table) {
            // Add indexes for saved posts table
            if (!$this->indexExists('saved_posts', 'saved_posts_user_id_post_id_index')) {
                $table->index(['user_id', 'post_id']); // For checking if user saved post
            }
            if (!$this->indexExists('saved_posts', 'saved_posts_post_id_created_at_index')) {
                $table->index(['post_id', 'created_at']); // For post saves timeline
            }
        });
    }

    /**
     * Check if an index exists on a table
     */
    protected function indexExists(string $table, string $index): bool
    {
        $indexes = \DB::select("SHOW INDEX FROM {$table}");
        foreach ($indexes as $indexInfo) {
            if ($indexInfo->Key_name === $index) {
                return true;
            }
        }
        return false;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['created_at']);
            $table->dropIndex(['likes_count', 'created_at']);
            $table->dropIndex(['media_type', 'created_at']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        Schema::table('post_likes', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['user_id', 'post_id']);
            $table->dropIndex(['post_id', 'created_at']);
        });

        Schema::table('comments', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['post_id', 'created_at']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        Schema::table('follows', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['follower_id', 'created_at']);
            $table->dropIndex(['following_id', 'created_at']);
        });

        Schema::table('saved_posts', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['user_id', 'post_id']);
            $table->dropIndex(['post_id', 'created_at']);
        });
    }
};
