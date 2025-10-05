<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        $posts = [
            [
                'content' => 'Just had an amazing day at the beach! The sunset was absolutely breathtaking. 🌅 #beach #sunset #nature',
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            ],
            [
                'content' => 'Working on a new project and feeling super motivated! Can\'t wait to share the results with everyone. 💻✨',
                'image_url' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800&h=600&fit=crop',
            ],
            [
                'content' => 'Coffee and coding - the perfect combination for a productive day! ☕️ #coding #productivity',
                'image_url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800&h=600&fit=crop',
            ],
            [
                'content' => 'Just finished reading an incredible book. Highly recommend it to anyone looking for inspiration! 📚',
                'image_url' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&h=600&fit=crop',
            ],
            [
                'content' => 'Morning workout complete! Starting the day with energy and positivity. 💪 #fitness #motivation',
                'image_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop',
            ],
            [
                'content' => 'Cooking experiment tonight - trying a new recipe. Wish me luck! 🍳 #cooking #foodie',
                'image_url' => 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=800&h=600&fit=crop',
            ],
            [
                'content' => 'Beautiful day for a hike in the mountains. Nature never fails to amaze me! 🏔️ #hiking #nature',
                'image_url' => 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&h=600&fit=crop',
            ],
            [
                'content' => 'Learning something new every day. Today\'s focus: mastering a new programming language! 🚀',
                'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=600&fit=crop',
            ],
            [
                'content' => 'Spending quality time with family. These moments are priceless! ❤️ #family #love',
                'image_url' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=800&h=600&fit=crop',
            ],
            [
                'content' => 'Just discovered an amazing new artist. Music has the power to change your mood instantly! 🎵',
                'image_url' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=800&h=600&fit=crop',
            ],
            [
                'content' => 'Working from home setup is finally complete! Productivity levels are through the roof! 🏠💼',
                'image_url' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?w=800&h=600&fit=crop',
            ],
            [
                'content' => 'Weekend plans: exploring the city and trying new restaurants. Life is an adventure! 🍽️',
                'image_url' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&h=600&fit=crop',
            ],
            [
                'content' => 'Grateful for all the opportunities that have come my way. Hard work pays off! 🙏',
                'image_url' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            ],
            [
                'content' => 'Just finished a challenging puzzle. The satisfaction of completing it is unmatched! 🧩',
                'image_url' => 'https://images.unsplash.com/photo-1606092195730-5d7b9af1efc5?w=800&h=600&fit=crop',
            ],
            [
                'content' => 'Rainy day vibes - perfect for staying indoors and catching up on some reading! 📖☔️',
                'image_url' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&h=600&fit=crop',
            ],
        ];

        foreach ($posts as $postData) {
            Post::create([
                'user_id' => $users->random()->id,
                'content' => $postData['content'],
                'media_type' => 'image',
                'media_url' => $postData['image_url'],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
