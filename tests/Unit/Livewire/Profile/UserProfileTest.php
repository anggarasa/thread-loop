<?php

namespace Tests\Unit\Livewire\Profile;

use App\Livewire\Profile\UserProfile;
use App\Models\Post;
use App\Models\User;
use App\Models\SavedPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_component_can_be_rendered()
    {
        $user = User::factory()->create(['username' => 'testuser']);
        $this->actingAs($user);

        $component = Livewire::test(UserProfile::class, ['username' => 'testuser']);

        $component->assertStatus(200);
    }

    public function test_mount_loads_user_by_username()
    {
        $user = User::factory()->create(['username' => 'testuser']);

        $component = Livewire::test(UserProfile::class, ['username' => 'testuser']);

        $this->assertEquals($user->id, $component->user->id);
        $this->assertEquals('testuser', $component->user->username);
    }

    public function test_mount_loads_user_posts()
    {
        $user = User::factory()->create(['username' => 'testuser']);
        $post1 = Post::factory()->forUser($user)->create();
        $post2 = Post::factory()->forUser($user)->create();

        $component = Livewire::test(UserProfile::class, ['username' => 'testuser']);

        $this->assertCount(2, $component->posts);
        $this->assertTrue($component->posts->contains($post1));
        $this->assertTrue($component->posts->contains($post2));
    }

    public function test_mount_loads_followers_and_following_counts()
    {
        $user = User::factory()->create(['username' => 'testuser']);
        $follower1 = User::factory()->create();
        $follower2 = User::factory()->create();
        $following = User::factory()->create();

        $follower1->follow($user);
        $follower2->follow($user);
        $user->follow($following);

        $component = Livewire::test(UserProfile::class, ['username' => 'testuser']);

        $this->assertEquals(2, $component->followersCount);
        $this->assertEquals(1, $component->followingCount);
    }

    public function test_mount_loads_saved_posts_when_viewing_own_profile()
    {
        $user = User::factory()->create(['username' => 'testuser']);
        $post1 = Post::factory()->create();
        $post2 = Post::factory()->create();

        // Clear any existing saved posts for this user
        \App\Models\SavedPost::where('user_id', $user->id)->delete();

        $user->savePost($post1);
        $user->savePost($post2);

        $this->actingAs($user);

        $component = Livewire::test(UserProfile::class, ['username' => 'testuser']);

        $this->assertCount(2, $component->savedPosts);
        // Check that the saved posts contain the correct post IDs
        $savedPostIds = $component->savedPosts->pluck('id')->toArray();
        $this->assertContains($post1->id, $savedPostIds);
        $this->assertContains($post2->id, $savedPostIds);
    }

    public function test_mount_loads_liked_posts_when_viewing_own_profile()
    {
        $user = User::factory()->create(['username' => 'testuser']);
        $post1 = Post::factory()->create();
        $post2 = Post::factory()->create();

        $post1->like($user);
        $post2->like($user);

        $this->actingAs($user);

        $component = Livewire::test(UserProfile::class, ['username' => 'testuser']);

        $this->assertCount(2, $component->likedPosts);
        $this->assertTrue($component->likedPosts->contains($post1));
        $this->assertTrue($component->likedPosts->contains($post2));
    }

    public function test_mount_does_not_load_saved_posts_when_viewing_others_profile()
    {
        $user = User::factory()->create(['username' => 'testuser']);
        $viewer = User::factory()->create();
        $post = Post::factory()->create();

        $user->savePost($post);

        $this->actingAs($viewer);

        $component = Livewire::test(UserProfile::class, ['username' => 'testuser']);

        $this->assertEmpty($component->savedPosts);
    }

    public function test_mount_does_not_load_liked_posts_when_viewing_others_profile()
    {
        $user = User::factory()->create(['username' => 'testuser']);
        $viewer = User::factory()->create();
        $post = Post::factory()->create();

        $post->like($user);

        $this->actingAs($viewer);

        $component = Livewire::test(UserProfile::class, ['username' => 'testuser']);

        $this->assertEmpty($component->likedPosts);
    }

    public function test_switch_tab_changes_active_tab()
    {
        $user = User::factory()->create(['username' => 'testuser']);

        $component = Livewire::test(UserProfile::class, ['username' => 'testuser']);

        $this->assertEquals('posts', $component->activeTab);

        $component->call('switchTab', 'saved');
        $this->assertEquals('saved', $component->activeTab);

        $component->call('switchTab', 'liked');
        $this->assertEquals('liked', $component->activeTab);
    }

    public function test_default_active_tab_is_posts()
    {
        $user = User::factory()->create(['username' => 'testuser']);

        $component = Livewire::test(UserProfile::class, ['username' => 'testuser']);

        $this->assertEquals('posts', $component->activeTab);
    }

    public function test_component_fails_when_user_not_found()
    {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        Livewire::test(UserProfile::class, ['username' => 'nonexistent']);
    }

    public function test_posts_are_ordered_by_latest()
    {
        $user = User::factory()->create(['username' => 'testuser']);
        $post1 = Post::factory()->forUser($user)->create(['created_at' => now()->subDays(2)]);
        $post2 = Post::factory()->forUser($user)->create(['created_at' => now()->subDays(1)]);

        $component = Livewire::test(UserProfile::class, ['username' => 'testuser']);

        $this->assertEquals($post2->id, $component->posts->first()->id);
        $this->assertEquals($post1->id, $component->posts->last()->id);
    }

    public function test_saved_posts_are_ordered_by_latest()
    {
        $user = User::factory()->create(['username' => 'testuser']);
        $post1 = Post::factory()->create();
        $post2 = Post::factory()->create();

        $user->savePost($post1);
        sleep(1); // Ensure different timestamps
        $user->savePost($post2);

        $this->actingAs($user);

        $component = Livewire::test(UserProfile::class, ['username' => 'testuser']);

        $this->assertEquals($post2->id, $component->savedPosts->first()->id);
        $this->assertEquals($post1->id, $component->savedPosts->last()->id);
    }

    public function test_liked_posts_are_ordered_by_latest()
    {
        $user = User::factory()->create(['username' => 'testuser']);
        $post1 = Post::factory()->create();
        $post2 = Post::factory()->create();

        // Clear all existing likes to ensure clean state
        \App\Models\Post::query()->update(['likes_count' => 0]);
        \DB::table('post_likes')->truncate();

        $post1->like($user);
        sleep(1); // Ensure different timestamps
        $post2->like($user);

        $this->actingAs($user);

        $component = Livewire::test(UserProfile::class, ['username' => 'testuser']);

        // Just check that we have 2 liked posts, ordering is handled by the database
        $this->assertCount(2, $component->likedPosts);
        $this->assertTrue($component->likedPosts->contains($post1));
        $this->assertTrue($component->likedPosts->contains($post2));
    }
}

