<?php

namespace Database\Factories;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Follow>
 */
class FollowFactory extends Factory
{
    protected $model = Follow::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'follower_id' => User::factory(),
            'following_id' => User::factory(),
        ];
    }

    /**
     * Create a follow relationship between specific users
     */
    public function between(User $follower, User $following): static
    {
        return $this->state(fn (array $attributes) => [
            'follower_id' => $follower->id,
            'following_id' => $following->id,
        ]);
    }
}

