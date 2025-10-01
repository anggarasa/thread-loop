<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Only create image posts (no text-only posts)
        $mediaType = 'image';

        // Generate image media URL
        $mediaUrl = $this->generateMediaUrl($mediaType);

        return [
            'user_id' => User::factory(),
            'content' => $this->faker->sentence(rand(5, 15)),
            'media_type' => $mediaType,
            'media_path' => $this->generateMediaPath($mediaType),
            'media_url' => $mediaUrl,
            'likes_count' => $this->faker->numberBetween(0, 1000),
            'comments_count' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Generate a media URL for images
     */
    private function generateMediaUrl(string $mediaType): string
    {
        // Use Picsum for random images
        $width = $this->faker->numberBetween(400, 1200);
        $height = $this->faker->numberBetween(300, 800);
        return "https://picsum.photos/{$width}/{$height}?random=" . $this->faker->numberBetween(1, 1000);
    }

    /**
     * Generate a media path for storage
     */
    private function generateMediaPath(string $mediaType): string
    {
        return 'posts/images/' . $this->faker->uuid() . '.jpg';
    }

    /**
     * Create an image post (redundant since all posts are images now)
     */
    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'media_type' => 'image',
            'media_url' => $this->generateMediaUrl('image'),
            'media_path' => $this->generateMediaPath('image'),
        ]);
    }

    /**
     * Create a post with specific user
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
