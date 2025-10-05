<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@threadloop.com',
            'password' => Hash::make('password'),
            'profile_url' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&h=400&fit=crop&crop=face',
            'email_verified_at' => now(),
        ]);

        // Sample users
        $users = [
            [
                'name' => 'John Doe',
                'username' => 'johndoe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'profile_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop&crop=face',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'username' => 'janesmith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'profile_url' => 'https://images.unsplash.com/photo-1494790108755-2616b612b786?w=400&h=400&fit=crop&crop=face',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mike Johnson',
                'username' => 'mikej',
                'email' => 'mike@example.com',
                'password' => Hash::make('password'),
                'profile_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop&crop=face',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Sarah Wilson',
                'username' => 'sarahw',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password'),
                'profile_url' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=400&fit=crop&crop=face',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'David Brown',
                'username' => 'davidb',
                'email' => 'david@example.com',
                'password' => Hash::make('password'),
                'profile_url' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400&h=400&fit=crop&crop=face',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lisa Garcia',
                'username' => 'lisag',
                'email' => 'lisa@example.com',
                'password' => Hash::make('password'),
                'profile_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=400&h=400&fit=crop&crop=face',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Tom Anderson',
                'username' => 'tomanderson',
                'email' => 'tom@example.com',
                'password' => Hash::make('password'),
                'profile_url' => 'https://images.unsplash.com/photo-1519345182560-3f2917c472ef?w=400&h=400&fit=crop&crop=face',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Emma Davis',
                'username' => 'emmad',
                'email' => 'emma@example.com',
                'password' => Hash::make('password'),
                'profile_url' => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=400&h=400&fit=crop&crop=face',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Alex Rodriguez',
                'username' => 'alexr',
                'email' => 'alex@example.com',
                'password' => Hash::make('password'),
                'profile_url' => 'https://images.unsplash.com/photo-1507591064344-4c6ce005b128?w=400&h=400&fit=crop&crop=face',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Maria Lopez',
                'username' => 'marial',
                'email' => 'maria@example.com',
                'password' => Hash::make('password'),
                'profile_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&h=400&fit=crop&crop=face',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
