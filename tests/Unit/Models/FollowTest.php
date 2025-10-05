<?php

namespace Tests\Unit\Models;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    public function test_follow_can_be_created()
    {
        $follower = User::factory()->create();
        $following = User::factory()->create();

        $follow = Follow::factory()->between($follower, $following)->create();

        $this->assertInstanceOf(Follow::class, $follow);
        $this->assertEquals($follower->id, $follow->follower_id);
        $this->assertEquals($following->id, $follow->following_id);
    }

    public function test_follow_belongs_to_follower()
    {
        $follower = User::factory()->create();
        $following = User::factory()->create();
        $follow = Follow::factory()->between($follower, $following)->create();

        $this->assertInstanceOf(User::class, $follow->follower);
        $this->assertEquals($follower->id, $follow->follower->id);
    }

    public function test_follow_belongs_to_following()
    {
        $follower = User::factory()->create();
        $following = User::factory()->create();
        $follow = Follow::factory()->between($follower, $following)->create();

        $this->assertInstanceOf(User::class, $follow->following);
        $this->assertEquals($following->id, $follow->following->id);
    }

    public function test_follow_fillable_attributes()
    {
        $follow = new Follow();
        $fillable = $follow->getFillable();

        $this->assertContains('follower_id', $fillable);
        $this->assertContains('following_id', $fillable);
    }

    public function test_follow_relationship_works_correctly()
    {
        $follower = User::factory()->create();
        $following = User::factory()->create();

        $follow = Follow::factory()->between($follower, $following)->create();

        // Test follower relationship
        $this->assertEquals($follower->name, $follow->follower->name);
        $this->assertEquals($follower->email, $follow->follower->email);

        // Test following relationship
        $this->assertEquals($following->name, $follow->following->name);
        $this->assertEquals($following->email, $follow->following->email);
    }
}

