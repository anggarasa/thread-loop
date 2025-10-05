<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PostLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $posts = Post::all();

        // Buat likes untuk setiap post (random 5-25 likes per post)
        foreach ($posts as $post) {
            $maxLikes = min(25, $users->count()); // Pastikan tidak melebihi jumlah user
            $likeCount = rand(5, $maxLikes);
            $usersWhoLiked = $users->random($likeCount);

            foreach ($usersWhoLiked as $user) {
                // Cek apakah user sudah like post ini
                $existingLike = DB::table('post_likes')
                    ->where('user_id', $user->id)
                    ->where('post_id', $post->id)
                    ->first();

                if (!$existingLike) {
                    DB::table('post_likes')->insert([
                        'user_id' => $user->id,
                        'post_id' => $post->id,
                        'created_at' => $post->created_at->addMinutes(rand(1, 1440)),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
