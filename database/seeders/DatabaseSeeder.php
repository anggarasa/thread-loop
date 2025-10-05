<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run all seeders in the correct order
        $this->call([
            UserSeeder::class,        // Create users first
            PostSeeder::class,        // Create regular posts with images
            //VideoPostSeeder::class,   // Create video posts
            CommentSeeder::class,     // Create comments
            FollowSeeder::class,      // Create follow relationships
            PostLikeSeeder::class,    // Create post likes
            SavedPostSeeder::class,   // Create saved posts
        ]);
    }
}
