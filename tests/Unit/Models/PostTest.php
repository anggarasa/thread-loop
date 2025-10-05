<?php

namespace Tests\Unit\Models;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_can_be_created()
    {
        $user = User::factory()->create();
        $post = Post::factory()->forUser($user)->create([
            'content' => 'Test post content',
            'media_type' => 'image',
            'media_url' => 'https://example.com/image.jpg',
        ]);

        $this->assertInstanceOf(Post::class, $post);
        $this->assertEquals('Test post content', $post->content);
        $this->assertEquals('image', $post->media_type);
        $this->assertEquals('https://example.com/image.jpg', $post->media_url);
        $this->assertEquals($user->id, $post->user_id);
    }

    public function test_post_belongs_to_user()
    {
        $user = User::factory()->create();
        $post = Post::factory()->forUser($user)->create();

        $this->assertInstanceOf(User::class, $post->user);
        $this->assertEquals($user->id, $post->user->id);
    }

    public function test_post_has_comments()
    {
        $post = Post::factory()->create();
        $comment1 = Comment::factory()->forPost($post)->create();
        $comment2 = Comment::factory()->forPost($post)->create();

        $this->assertTrue($post->comments->contains($comment1));
        $this->assertTrue($post->comments->contains($comment2));
        $this->assertEquals(2, $post->comments->count());
    }

    public function test_post_has_likes()
    {
        $post = Post::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $post->like($user1);
        $post->like($user2);

        $this->assertTrue($post->likes->contains($user1));
        $this->assertTrue($post->likes->contains($user2));
        $this->assertEquals(2, $post->likes->count());
    }

    public function test_is_text_post()
    {
        $post = Post::factory()->create(['media_type' => null]);
        $this->assertTrue($post->isTextPost());

        $post = Post::factory()->create(['media_type' => 'image']);
        $this->assertFalse($post->isTextPost());
    }

    public function test_is_image_post()
    {
        $post = Post::factory()->create(['media_type' => 'image']);
        $this->assertTrue($post->isImagePost());

        $post = Post::factory()->create(['media_type' => 'video']);
        $this->assertFalse($post->isImagePost());

        $post = Post::factory()->create(['media_type' => null]);
        $this->assertFalse($post->isImagePost());
    }

    public function test_is_video_post()
    {
        $post = Post::factory()->create(['media_type' => 'video']);
        $this->assertTrue($post->isVideoPost());

        $post = Post::factory()->create(['media_type' => 'image']);
        $this->assertFalse($post->isVideoPost());

        $post = Post::factory()->create(['media_type' => null]);
        $this->assertFalse($post->isVideoPost());
    }

    public function test_post_can_be_liked_by_user()
    {
        $post = Post::factory()->create(['likes_count' => 0]);
        $user = User::factory()->create();

        $this->assertFalse($post->isLikedBy($user));

        $post->like($user);

        $this->assertTrue($post->fresh()->isLikedBy($user));
        $this->assertEquals(1, $post->fresh()->likes_count);
    }

    public function test_post_cannot_be_liked_twice_by_same_user()
    {
        $post = Post::factory()->create(['likes_count' => 0]);
        $user = User::factory()->create();

        $post->like($user);
        $post->like($user); // Try to like again

        $this->assertTrue($post->fresh()->isLikedBy($user));
        $this->assertEquals(1, $post->fresh()->likes_count);
    }

    public function test_post_can_be_unliked_by_user()
    {
        $post = Post::factory()->create(['likes_count' => 0]);
        $user = User::factory()->create();

        $post->like($user);
        $this->assertTrue($post->fresh()->isLikedBy($user));
        $this->assertEquals(1, $post->fresh()->likes_count);

        $post->unlike($user);
        $this->assertFalse($post->fresh()->isLikedBy($user));
        $this->assertEquals(0, $post->fresh()->likes_count);
    }

    public function test_post_can_be_saved_by_user()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();

        $this->assertFalse($post->isSavedBy($user));

        $post->saveBy($user);

        $this->assertTrue($post->isSavedBy($user));
    }

    public function test_post_cannot_be_saved_twice_by_same_user()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();

        $post->saveBy($user);
        $post->saveBy($user); // Try to save again

        $this->assertTrue($post->isSavedBy($user));
    }

    public function test_post_can_be_unsaved_by_user()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();

        $post->saveBy($user);
        $this->assertTrue($post->isSavedBy($user));

        $post->unsaveBy($user);
        $this->assertFalse($post->isSavedBy($user));
    }

    public function test_saved_by_relationship()
    {
        $post = Post::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $post->saveBy($user1);
        $post->saveBy($user2);

        $this->assertTrue($post->savedBy->contains($user1));
        $this->assertTrue($post->savedBy->contains($user2));
        $this->assertEquals(2, $post->savedBy->count());
    }

    public function test_scope_for_feed()
    {
        // Create posts with different likes and comments counts
        $post1 = Post::factory()->create([
            'likes_count' => 100,
            'comments_count' => 50,
            'created_at' => now()->subDays(5),
        ]);

        $post2 = Post::factory()->create([
            'likes_count' => 50,
            'comments_count' => 100,
            'created_at' => now()->subDays(10),
        ]);

        $post3 = Post::factory()->create([
            'likes_count' => 200,
            'comments_count' => 0,
            'created_at' => now()->subDays(40), // Outside 30-day range
        ]);

        $feedPosts = Post::forFeed()->get();

        // Should only include posts within 30 days
        $this->assertTrue($feedPosts->contains($post1));
        $this->assertTrue($feedPosts->contains($post2));
        $this->assertFalse($feedPosts->contains($post3));

        // Should be ordered by weighted score (likes * 0.7 + comments * 0.3)
        // post1: 100 * 0.7 + 50 * 0.3 = 85
        // post2: 50 * 0.7 + 100 * 0.3 = 65
        $this->assertEquals($post1->id, $feedPosts->first()->id);
    }

    public function test_scope_recent()
    {
        $post1 = Post::factory()->create(['created_at' => now()->subDays(3)]);
        $post2 = Post::factory()->create(['created_at' => now()->subDays(10)]);

        $recentPosts = Post::recent()->get();

        $this->assertTrue($recentPosts->contains($post1));
        $this->assertFalse($recentPosts->contains($post2));
    }

    public function test_scope_by_media_type()
    {
        $imagePost = Post::factory()->create(['media_type' => 'image']);
        $videoPost = Post::factory()->create(['media_type' => 'video']);
        $textPost = Post::factory()->create(['media_type' => null]);

        $imagePosts = Post::byMediaType('image')->get();
        $videoPosts = Post::byMediaType('video')->get();

        $this->assertTrue($imagePosts->contains($imagePost));
        $this->assertFalse($imagePosts->contains($videoPost));
        $this->assertFalse($imagePosts->contains($textPost));

        $this->assertTrue($videoPosts->contains($videoPost));
        $this->assertFalse($videoPosts->contains($imagePost));
        $this->assertFalse($videoPosts->contains($textPost));
    }
}
