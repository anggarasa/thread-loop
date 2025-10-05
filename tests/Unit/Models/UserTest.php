<?php

namespace Tests\Unit\Models;

use App\Models\Follow;
use App\Models\Post;
use App\Models\SavedPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'username' => 'john.doe',
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertEquals('john.doe', $user->username);
    }

    public function test_user_generates_correct_initials()
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $this->assertEquals('JD', $user->initials());

        $user = User::factory()->create(['name' => 'Jane']);
        $this->assertEquals('J', $user->initials());

        $user = User::factory()->create(['name' => 'Mary Jane Smith']);
        $this->assertEquals('MJ', $user->initials());
    }

    public function test_generate_username_from_name()
    {
        $this->assertEquals('john.doe', User::generateUsername('John Doe'));
        $this->assertEquals('jane.smith', User::generateUsername('Jane Smith'));
        $this->assertEquals('user', User::generateUsername(''));
        $this->assertEquals('user', User::generateUsername('!@#$%'));
        $this->assertEquals('john.doe', User::generateUsername('John   Doe'));
        $this->assertEquals('john.doe', User::generateUsername('John...Doe'));
    }

    public function test_generate_unique_username()
    {
        // Create a user with existing username
        User::factory()->create(['username' => 'john.doe']);

        $uniqueUsername = User::generateUniqueUsername('John Doe');
        $this->assertEquals('john.doe1', $uniqueUsername);

        // Create another user with the new username
        User::factory()->create(['username' => 'john.doe1']);

        $uniqueUsername = User::generateUniqueUsername('John Doe');
        $this->assertEquals('john.doe2', $uniqueUsername);
    }

    public function test_generate_profile_url()
    {
        $user = User::factory()->create(['username' => 'john.doe']);
        $expectedUrl = url("/profile/john.doe");
        
        $this->assertEquals($expectedUrl, $user->generateProfileUrl());
    }

    public function test_user_has_posts_relationship()
    {
        $user = User::factory()->create();
        $post = Post::factory()->forUser($user)->create();

        $this->assertTrue($user->posts->contains($post));
        $this->assertEquals(1, $user->posts->count());
    }

    public function test_user_can_follow_another_user()
    {
        $follower = User::factory()->create();
        $following = User::factory()->create();

        $follower->follow($following);

        $this->assertTrue($follower->follows($following));
        $this->assertDatabaseHas('follows', [
            'follower_id' => $follower->id,
            'following_id' => $following->id,
        ]);
    }

    public function test_user_cannot_follow_themselves()
    {
        $user = User::factory()->create();

        $user->follow($user);

        $this->assertFalse($user->follows($user));
        $this->assertDatabaseMissing('follows', [
            'follower_id' => $user->id,
            'following_id' => $user->id,
        ]);
    }

    public function test_user_cannot_follow_same_user_twice()
    {
        $follower = User::factory()->create();
        $following = User::factory()->create();

        $follower->follow($following);
        $follower->follow($following); // Try to follow again

        $followCount = Follow::where('follower_id', $follower->id)
            ->where('following_id', $following->id)
            ->count();

        $this->assertEquals(1, $followCount);
    }

    public function test_user_can_unfollow_another_user()
    {
        $follower = User::factory()->create();
        $following = User::factory()->create();

        $follower->follow($following);
        $this->assertTrue($follower->follows($following));

        $follower->unfollow($following);
        $this->assertFalse($follower->follows($following));
    }

    public function test_followers_count()
    {
        $user = User::factory()->create();
        $follower1 = User::factory()->create();
        $follower2 = User::factory()->create();

        $follower1->follow($user);
        $follower2->follow($user);

        $this->assertEquals(2, $user->followersCount());
    }

    public function test_following_count()
    {
        $user = User::factory()->create();
        $following1 = User::factory()->create();
        $following2 = User::factory()->create();

        $user->follow($following1);
        $user->follow($following2);

        $this->assertEquals(2, $user->followingCount());
    }

    public function test_user_can_save_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $user->savePost($post);

        $this->assertTrue($user->hasSavedPost($post));
        $this->assertDatabaseHas('saved_posts', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_user_cannot_save_same_post_twice()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $user->savePost($post);
        $user->savePost($post); // Try to save again

        $savedCount = SavedPost::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->count();

        $this->assertEquals(1, $savedCount);
    }

    public function test_user_can_unsave_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $user->savePost($post);
        $this->assertTrue($user->hasSavedPost($post));

        $user->unsavePost($post);
        $this->assertFalse($user->hasSavedPost($post));
    }

    public function test_saved_posts_relationship()
    {
        $user = User::factory()->create();
        $post1 = Post::factory()->create();
        $post2 = Post::factory()->create();

        $user->savePost($post1);
        $user->savePost($post2);

        $savedPosts = $user->fresh()->savedPostsWithPost()->get();
        
        $this->assertCount(2, $savedPosts);
        $this->assertEquals($post1->id, $savedPosts->first()->post->id);
        $this->assertEquals($post2->id, $savedPosts->last()->post->id);
    }

    public function test_liked_posts_relationship()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $post->like($user);

        $this->assertTrue($user->likedPosts->contains($post));
    }

    public function test_following_relationship()
    {
        $follower = User::factory()->create();
        $following = User::factory()->create();

        $follower->follow($following);

        $follows = $follower->fresh()->following;
        $this->assertCount(1, $follows);
        $this->assertEquals($following->id, $follows->first()->following_id);
    }

    public function test_followers_relationship()
    {
        $user = User::factory()->create();
        $follower = User::factory()->create();

        $follower->follow($user);

        $followers = $user->fresh()->followers;
        $this->assertCount(1, $followers);
        $this->assertEquals($follower->id, $followers->first()->follower_id);
    }
}
