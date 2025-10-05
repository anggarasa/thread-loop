<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Follow;
use App\Models\User;

class FollowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        // Pastikan setiap user mengikuti beberapa user lain (kecuali diri sendiri)
        foreach ($users as $user) {
            // Setiap user akan mengikuti 3-8 user lain secara random
            $followCount = rand(3, 8);
            $usersToFollow = $users->where('id', '!=', $user->id)->random($followCount);

            foreach ($usersToFollow as $userToFollow) {
                // Cek apakah sudah ada relasi follow untuk menghindari duplikasi
                $existingFollow = Follow::where('follower_id', $user->id)
                    ->where('following_id', $userToFollow->id)
                    ->first();

                if (!$existingFollow) {
                    Follow::create([
                        'follower_id' => $user->id,
                        'following_id' => $userToFollow->id,
                        'created_at' => now()->subDays(rand(1, 30)),
                    ]);
                }
            }
        }

        // Buat beberapa follow tambahan untuk membuat network lebih kompleks
        $additionalFollows = rand(20, 40);

        for ($i = 0; $i < $additionalFollows; $i++) {
            $follower = $users->random();
            $following = $users->where('id', '!=', $follower->id)->random();

            // Cek apakah sudah ada relasi follow
            $existingFollow = Follow::where('follower_id', $follower->id)
                ->where('following_id', $following->id)
                ->first();

            if (!$existingFollow) {
                Follow::create([
                    'follower_id' => $follower->id,
                    'following_id' => $following->id,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
