<?php

namespace Tests\Unit\Models;

use App\Models\SavedPost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SavedPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_saved_post_can_be_created()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $savedPost = SavedPost::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $this->assertInstanceOf(SavedPost::class, $savedPost);
        $this->assertEquals($user->id, $savedPost->user_id);
        $this->assertEquals($post->id, $savedPost->post_id);
    }

    public function test_saved_post_belongs_to_user()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $savedPost = SavedPost::factory()->forUser($user)->create();

        $this->assertInstanceOf(User::class, $savedPost->user);
        $this->assertEquals($user->id, $savedPost->user->id);
    }

    public function test_saved_post_belongs_to_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $savedPost = SavedPost::factory()->forPost($post)->create();

        $this->assertInstanceOf(Post::class, $savedPost->post);
        $this->assertEquals($post->id, $savedPost->post->id);
    }

    public function test_saved_post_has_timestamps()
    {
        $savedPost = SavedPost::factory()->create();

        $this->assertNotNull($savedPost->created_at);
        $this->assertNotNull($savedPost->updated_at);
    }

    public function test_saved_post_fillable_attributes()
    {
        $savedPost = new SavedPost();
        $fillable = $savedPost->getFillable();

        $this->assertContains('user_id', $fillable);
        $this->assertContains('post_id', $fillable);
    }

    public function test_saved_post_casts_timestamps_to_datetime()
    {
        $savedPost = SavedPost::factory()->create();

        $this->assertInstanceOf(\Carbon\Carbon::class, $savedPost->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $savedPost->updated_at);
    }

    public function test_saved_post_relationship_works_correctly()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $savedPost = SavedPost::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        // Test user relationship
        $this->assertEquals($user->name, $savedPost->user->name);
        $this->assertEquals($user->email, $savedPost->user->email);

        // Test post relationship
        $this->assertEquals($post->content, $savedPost->post->content);
        $this->assertEquals($post->user_id, $savedPost->post->user_id);
    }
}

