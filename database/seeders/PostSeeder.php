<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users to create posts for them
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $this->command->info('Creating posts with images only...');

        // Create posts for each user
        foreach ($users as $user) {
            // Create 3-8 posts per user
            $postCount = rand(3, 8);

            for ($i = 0; $i < $postCount; $i++) {
                // Create image posts only
                Post::factory()
                    ->forUser($user)
                    ->create();
            }
        }

        // Create some additional random posts
        $additionalPosts = rand(20, 50);
        Post::factory($additionalPosts)->create();

        $totalPosts = Post::count();
        $imagePosts = Post::where('media_type', 'image')->count();
        $textPosts = Post::whereNull('media_type')->count();

        $this->command->info("Created {$totalPosts} posts:");
        $this->command->info("- {$imagePosts} image posts");
        $this->command->info("- {$textPosts} text-only posts (should be 0)");

        if ($textPosts > 0) {
            $this->command->warn("Warning: Found {$textPosts} text-only posts. This shouldn't happen with the current factory.");
        }
    }
}
