<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_can_be_created()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        
        $comment = Comment::factory()->create([
            'content' => 'This is a test comment',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals('This is a test comment', $comment->content);
        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($post->id, $comment->post_id);
    }

    public function test_comment_belongs_to_post()
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->forPost($post)->create();

        $this->assertInstanceOf(Post::class, $comment->post);
        $this->assertEquals($post->id, $comment->post->id);
    }

    public function test_comment_belongs_to_user()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->byUser($user)->create();

        $this->assertInstanceOf(User::class, $comment->user);
        $this->assertEquals($user->id, $comment->user->id);
    }

    public function test_comment_has_timestamps()
    {
        $comment = Comment::factory()->create();

        $this->assertNotNull($comment->created_at);
        $this->assertNotNull($comment->updated_at);
    }

    public function test_comment_fillable_attributes()
    {
        $comment = new Comment();
        $fillable = $comment->getFillable();

        $this->assertContains('post_id', $fillable);
        $this->assertContains('user_id', $fillable);
        $this->assertContains('content', $fillable);
    }

    public function test_comment_casts_timestamps_to_datetime()
    {
        $comment = Comment::factory()->create();

        $this->assertInstanceOf(\Carbon\Carbon::class, $comment->created_at);
        $this->assertInstanceOf(\Carbon\Carbon::class, $comment->updated_at);
    }
}

