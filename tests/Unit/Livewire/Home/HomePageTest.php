<?php

namespace Tests\Unit\Livewire\Home;

use App\Livewire\Home\HomePage;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Notifications\PostLiked;
use App\Notifications\PostCommented;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    public function test_component_can_be_rendered()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);

        $component->assertStatus(200);
    }

    public function test_mount_loads_user_liked_and_saved_posts()
    {
        $user = User::factory()->create();
        $post1 = Post::factory()->create();
        $post2 = Post::factory()->create();

        // User likes post1 and saves post2
        $post1->like($user);
        $post2->saveBy($user);

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);

        $this->assertContains($post1->id, $component->likedPosts);
        $this->assertContains($post2->id, $component->savedPosts);
    }

    public function test_mount_loads_initial_posts()
    {
        $user = User::factory()->create();
        Post::factory()->count(15)->create(); // Create more posts to ensure hasMorePosts is true

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);

        $this->assertNotEmpty($component->posts);
        $this->assertEquals(2, $component->page); // Page is incremented after loading posts
        $this->assertTrue($component->hasMorePosts);
    }

    public function test_toggle_like_likes_post_when_not_liked()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);
        $component->call('toggleLike', $post->id);

        $this->assertTrue($post->fresh()->isLikedBy($user));
        $this->assertContains($post->id, $component->likedPosts);
    }

    public function test_toggle_like_unlikes_post_when_already_liked()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $post->like($user);

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);
        $component->call('toggleLike', $post->id);

        $this->assertFalse($post->fresh()->isLikedBy($user));
        $this->assertNotContains($post->id, $component->likedPosts);
    }

    public function test_toggle_like_sends_notification_when_liking_others_post()
    {
        $user = User::factory()->create();
        $postOwner = User::factory()->create();
        $post = Post::factory()->forUser($postOwner)->create();

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);
        $component->call('toggleLike', $post->id);

        Notification::assertSentTo($postOwner, PostLiked::class);
    }

    public function test_toggle_like_does_not_send_notification_when_liking_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->forUser($user)->create();

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);
        $component->call('toggleLike', $post->id);

        Notification::assertNotSentTo($user, PostLiked::class);
    }

    public function test_toggle_save_saves_post_when_not_saved()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);
        $component->call('toggleSave', $post->id);

        $this->assertTrue($post->fresh()->isSavedBy($user));
        $this->assertContains($post->id, $component->savedPosts);
    }

    public function test_toggle_save_unsaves_post_when_already_saved()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $post->saveBy($user);

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);
        $component->call('toggleSave', $post->id);

        $this->assertFalse($post->fresh()->isSavedBy($user));
        $this->assertNotContains($post->id, $component->savedPosts);
    }

    public function test_toggle_comments_shows_comments_when_hidden()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);
        $component->call('toggleComments', $post->id);

        $this->assertContains($post->id, $component->showComments);
    }

    public function test_toggle_comments_hides_comments_when_shown()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);
        $component->call('toggleComments', $post->id);
        $component->call('toggleComments', $post->id);

        $this->assertNotContains($post->id, $component->showComments);
    }

    public function test_load_comments_loads_post_comments()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment1 = Comment::factory()->forPost($post)->create();
        $comment2 = Comment::factory()->forPost($post)->create();

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);
        $component->call('loadComments', $post->id);

        $this->assertArrayHasKey($post->id, $component->comments);
        $this->assertCount(2, $component->comments[$post->id]);
    }

    public function test_add_comment_creates_comment_and_sends_notification()
    {
        $user = User::factory()->create();
        $postOwner = User::factory()->create();
        $post = Post::factory()->forUser($postOwner)->create();

        // Clear all existing comments to ensure clean state
        Comment::truncate();
        $post->update(['comments_count' => 0]);

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);
        $component->set("newComments.{$post->id}", 'This is a test comment');
        $component->call('addComment', $post->id);

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'This is a test comment',
        ]);

        $this->assertEquals(1, $post->fresh()->comments_count);
        Notification::assertSentTo($postOwner, PostCommented::class);
    }

    public function test_add_comment_validates_content()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);
        $component->set("newComments.{$post->id}", '');
        $component->call('addComment', $post->id);

        $component->assertHasErrors("newComments.{$post->id}");
    }

    public function test_is_liked_returns_correct_status()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $post->like($user);

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);

        $this->assertContains($post->id, $component->likedPosts);
        $this->assertNotContains(999, $component->likedPosts);
    }

    public function test_is_saved_returns_correct_status()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $post->saveBy($user);

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);

        $this->assertContains($post->id, $component->savedPosts);
        $this->assertNotContains(999, $component->savedPosts);
    }

    public function test_load_more_loads_additional_posts()
    {
        $user = User::factory()->create();
        Post::factory()->count(15)->create();

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);

        $initialCount = count($component->posts);
        $component->call('loadMore');

        $this->assertGreaterThan($initialCount, count($component->posts));
        $this->assertEquals(3, $component->page); // Page increments after loadMore
    }

    public function test_load_more_sets_has_more_posts_to_false_when_no_more_posts()
    {
        $user = User::factory()->create();
        Post::factory()->count(5)->create(); // Less than 10 posts

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);
        $component->call('loadMore');

        $this->assertFalse($component->hasMorePosts);
    }

    public function test_render_includes_suggested_users()
    {
        $user = User::factory()->create();
        User::factory()->count(3)->create();

        $this->actingAs($user);

        $component = Livewire::test(HomePage::class);

        $viewData = $component->viewData('suggestedUsers');
        $this->assertCount(3, $viewData);
    }
}
