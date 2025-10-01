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
        // Create users first
        User::factory(10)->create();

        User::create([
            'name' => 'anggara',
            'email' => 'anggasaputra6609@gmail.com',
            'password' => Hash::make('password'),
            'username' => 'anggara',
            'email_verified_at' => now(),
        ]);

        // Create posts with media (images and videos only)
        $this->call([
            PostSeeder::class,
        ]);
    }
}
