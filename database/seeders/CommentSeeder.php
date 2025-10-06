<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $posts = Post::all();

        $comments = [
            'Amazing post! Thanks for sharing! 😍',
            'This is so inspiring! Keep it up! 💪',
            'I totally agree with this! 👍',
            'Great content as always! 🔥',
            'This made my day! Thank you! 😊',
            'Wow, this is incredible! 🤩',
            'I learned something new today! 📚',
            'Perfect timing for this post! ⏰',
            'You\'re absolutely right! 💯',
            'This is exactly what I needed to see! ✨',
            'Love this perspective! 💭',
            'So true! Thanks for the reminder! 🙏',
            'This is beautiful! 🎨',
            'I can relate to this so much! 💕',
            'Great advice! Will definitely try this! 💡',
            'This is hilarious! 😂',
            'You always post the best content! 🌟',
            'This is so helpful! Thank you! 🙌',
            'I\'m sharing this with everyone! 📤',
            'This deserves more likes! ⬆️',
            'Perfect explanation! 👌',
            'This is pure gold! 🏆',
            'I\'m saving this for later! 💾',
            'This is exactly how I feel! 💯',
            'You\'re so talented! 🎭',
            'This is the content I live for! 🎯',
            'Amazing work! Keep creating! 🚀',
            'This is so well thought out! 🧠',
            'I\'m inspired by this! ✨',
            'This is game-changing! 🎮',
        ];

        // Buat komentar untuk setiap post (random 2-8 komentar per post)
        foreach ($posts as $post) {
            $commentCount = rand(2, 8);

            for ($i = 0; $i < $commentCount; $i++) {
                Comment::create([
                    'user_id' => $users->random()->id,
                    'post_id' => $post->id,
                    'content' => $comments[array_rand($comments)],
                    'created_at' => $post->created_at->addMinutes(rand(5, 1440)), // Komentar dibuat dalam 24 jam setelah post
                ]);
            }
        }

        // Buat beberapa komentar tambahan untuk membuat lebih banyak interaksi
        $additionalComments = [
            'Thanks for the kind words! 😊',
            'I\'m glad you found it helpful! 🙏',
            'You\'re welcome! Happy to help! 💕',
            'That means a lot! Thank you! 🌟',
            'I appreciate your comment! 💯',
            'So happy you enjoyed it! 🎉',
            'Your support means everything! ❤️',
            'Thank you for being here! 🤗',
            'This community is amazing! 🌈',
            'You\'re too kind! 😭',
        ];

        // Buat 20-30 komentar tambahan
        $additionalCount = rand(20, 30);
        for ($i = 0; $i < $additionalCount; $i++) {
            $randomPost = $posts->random();

            Comment::create([
                'user_id' => $users->random()->id,
                'post_id' => $randomPost->id,
                'content' => $additionalComments[array_rand($additionalComments)],
                'created_at' => $randomPost->created_at->addMinutes(rand(10, 1440)),
            ]);
        }

        // Update comments_count untuk setiap post berdasarkan data di comments
        foreach ($posts as $post) {
            $actualCommentsCount = DB::table('comments')
                ->where('post_id', $post->id)
                ->count();

            $post->update(['comments_count' => $actualCommentsCount]);
        }
    }
}
