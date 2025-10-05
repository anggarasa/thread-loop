<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SavedPost;
use App\Models\Post;
use App\Models\User;

class SavedPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $posts = Post::all();

        // Setiap user akan menyimpan 3-10 post secara random
        foreach ($users as $user) {
            $maxSaves = min(10, $posts->count()); // Pastikan tidak melebihi jumlah post
            $saveCount = rand(3, $maxSaves);
            $postsToSave = $posts->random($saveCount);

            foreach ($postsToSave as $post) {
                // Cek apakah post sudah disimpan oleh user ini
                $existingSave = SavedPost::where('user_id', $user->id)
                    ->where('post_id', $post->id)
                    ->first();

                if (!$existingSave) {
                    SavedPost::create([
                        'user_id' => $user->id,
                        'post_id' => $post->id,
                        'created_at' => $post->created_at->addMinutes(rand(10, 2880)), // Disimpan dalam 2 hari setelah post dibuat
                    ]);
                }
            }
        }
    }
}
