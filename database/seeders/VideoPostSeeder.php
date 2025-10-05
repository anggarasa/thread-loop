<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;

class VideoPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        // Array untuk link video - Anda bisa mengisi ini secara manual
        $videoLinks = [
            // Contoh link video (ganti dengan link video yang Anda inginkan)
            'https://www.tiktok.com/@areskun9/video/7556731274669362443?is_from_webapp=1&sender_device=pc',
            'https://www.tiktok.com/@arwildoo/video/7556161415829114132?is_from_webapp=1&sender_device=pc',
            'https://www.tiktok.com/@zankesshokuband/video/7557569451965582613?is_from_webapp=1&sender_device=pc',
            'https://www.tiktok.com/@zankesshokuband/video/7557345016075635989?is_from_webapp=1&sender_device=pc',
            'https://www.tiktok.com/@jobswebsites_/video/7554992498146413842?is_from_webapp=1&sender_device=pc',
            'https://www.tiktok.com/@abilhaqqq/video/7557564205776325909?is_from_webapp=1&sender_device=pc',
            'https://www.tiktok.com/@itz.al.star/video/7557540238185254156?is_from_webapp=1&sender_device=pc',
            'https://www.tiktok.com/@west_t1/video/7555224681561722123?is_from_webapp=1&sender_device=pc',
            'https://www.tiktok.com/@sombrsouls/video/7543386145829227794?is_from_webapp=1&sender_device=pc',
            'https://www.tiktok.com/@ekhakun263/video/7538367867134184721?is_from_webapp=1&sender_device=pc',
            // Tambahkan lebih banyak link video sesuai kebutuhan
        ];

        $videoPosts = [
            [
                'content' => 'Check out this amazing tutorial I just created! 🎥✨ #tutorial #learning',
            ],
            [
                'content' => 'Behind the scenes of my latest project. So much work goes into creating something great! 🎬',
            ],
            [
                'content' => 'Quick tip video for all the developers out there! Hope this helps! 💻 #coding #tips',
            ],
            [
                'content' => 'Cooking tutorial time! Learn how to make this delicious dish step by step! 👨‍🍳🍽️',
            ],
            [
                'content' => 'Fitness routine that changed my life. Sharing because it might help you too! 💪 #fitness #health',
            ],
            [
                'content' => 'Travel vlog from my recent adventure. The views were absolutely incredible! ✈️🌍',
            ],
            [
                'content' => 'Music production process - from idea to final track! 🎵🎧 #music #production',
            ],
            [
                'content' => 'Art process video. Watch me create this painting from start to finish! 🎨 #art #painting',
            ],
            [
                'content' => 'Gaming highlights from my latest session. Some epic moments here! 🎮 #gaming',
            ],
            [
                'content' => 'DIY project tutorial. Transform your space with these simple steps! 🔨 #diy #home',
            ],
            [
                'content' => 'Photography tips and tricks. Learn how to take better photos! 📸 #photography',
            ],
            [
                'content' => 'Dance routine I\'ve been working on. Practice makes perfect! 💃 #dance #performance',
            ],
            [
                'content' => 'Science experiment that will blow your mind! 🔬⚗️ #science #experiment',
            ],
            [
                'content' => 'Language learning techniques that actually work! 📚🗣️ #language #learning',
            ],
            [
                'content' => 'Pet training tips from a professional. Your furry friends will love these! 🐕🐱 #pets #training',
            ],
        ];

        // Pastikan kita tidak melebihi jumlah link video yang tersedia
        $maxPosts = min(count($videoLinks), count($videoPosts));

        for ($i = 0; $i < $maxPosts; $i++) {
            Post::create([
                'user_id' => $users->random()->id,
                'content' => $videoPosts[$i]['content'],
                'media_type' => 'video',
                'media_url' => $videoLinks[$i],
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Jika ada link video tambahan, buat postingan dengan konten generik
        if (count($videoLinks) > count($videoPosts)) {
            for ($i = count($videoPosts); $i < count($videoLinks); $i++) {
                Post::create([
                    'user_id' => $users->random()->id,
                    'content' => 'Amazing video content! Check this out! 🎥✨',
                    'media_type' => 'video',
                    'media_url' => $videoLinks[$i],
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
