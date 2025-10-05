<?php

namespace Tests\Unit\Notifications;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Notifications\PostLiked;
use App\Notifications\PostCommented;
use App\Notifications\UserFollowed;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\AnonymousNotifiable;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_liked_notification()
    {
        $actor = User::factory()->create(['name' => 'John Doe', 'username' => 'john.doe']);
        $post = Post::factory()->create(['content' => 'Test post content']);
        $notifiable = User::factory()->create();

        $notification = new PostLiked($actor, $post);

        $this->assertInstanceOf(PostLiked::class, $notification);
        $this->assertEquals($actor, $notification->actor);
        $this->assertEquals($post, $notification->post);
    }

    public function test_post_liked_notification_via_method()
    {
        $actor = User::factory()->create();
        $post = Post::factory()->create();
        $notification = new PostLiked($actor, $post);

        $this->assertEquals(['database'], $notification->via(new AnonymousNotifiable()));
    }

    public function test_post_liked_notification_to_array()
    {
        $actor = User::factory()->create([
            'name' => 'John Doe',
            'username' => 'john.doe',
            'profile_url' => '/profile/john.doe',
        ]);
        $post = Post::factory()->create(['content' => 'Test post content']);
        $notifiable = User::factory()->create();

        $notification = new PostLiked($actor, $post);
        $array = $notification->toArray($notifiable);

        $this->assertEquals('post_liked', $array['type']);
        $this->assertEquals($actor->id, $array['actor_id']);
        $this->assertEquals($actor->name, $array['actor_name']);
        $this->assertEquals($actor->username, $array['actor_username']);
        $this->assertEquals($actor->profile_url, $array['actor_profile_url']);
        $this->assertEquals($post->id, $array['post_id']);
        $this->assertEquals($post->content, $array['post_content']);
        $this->assertEquals($actor->name . ' liked your post', $array['message']);
    }

    public function test_post_commented_notification()
    {
        $actor = User::factory()->create(['name' => 'Jane Smith', 'username' => 'jane.smith']);
        $post = Post::factory()->create(['content' => 'Test post content']);
        $comment = Comment::factory()->create(['content' => 'Test comment']);
        $notifiable = User::factory()->create();

        $notification = new PostCommented($actor, $post, $comment);

        $this->assertInstanceOf(PostCommented::class, $notification);
        $this->assertEquals($actor, $notification->actor);
        $this->assertEquals($post, $notification->post);
        $this->assertEquals($comment, $notification->comment);
    }

    public function test_post_commented_notification_via_method()
    {
        $actor = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create();
        $notification = new PostCommented($actor, $post, $comment);

        $this->assertEquals(['database'], $notification->via(new AnonymousNotifiable()));
    }

    public function test_post_commented_notification_to_array()
    {
        $actor = User::factory()->create([
            'name' => 'Jane Smith',
            'username' => 'jane.smith',
            'profile_url' => '/profile/jane.smith',
        ]);
        $post = Post::factory()->create(['content' => 'Test post content']);
        $comment = Comment::factory()->create(['content' => 'Test comment']);
        $notifiable = User::factory()->create();

        $notification = new PostCommented($actor, $post, $comment);
        $array = $notification->toArray($notifiable);

        $this->assertEquals('post_commented', $array['type']);
        $this->assertEquals($actor->id, $array['actor_id']);
        $this->assertEquals($actor->name, $array['actor_name']);
        $this->assertEquals($actor->username, $array['actor_username']);
        $this->assertEquals($actor->profile_url, $array['actor_profile_url']);
        $this->assertEquals($post->id, $array['post_id']);
        $this->assertEquals($post->content, $array['post_content']);
        $this->assertEquals($comment->id, $array['comment_id']);
        $this->assertEquals($comment->content, $array['comment_content']);
        $this->assertEquals($actor->name . ' commented on your post', $array['message']);
    }

    public function test_user_followed_notification()
    {
        $actor = User::factory()->create(['name' => 'Bob Wilson', 'username' => 'bob.wilson']);
        $notifiable = User::factory()->create();

        $notification = new UserFollowed($actor);

        $this->assertInstanceOf(UserFollowed::class, $notification);
        $this->assertEquals($actor, $notification->actor);
    }

    public function test_user_followed_notification_via_method()
    {
        $actor = User::factory()->create();
        $notification = new UserFollowed($actor);

        $this->assertEquals(['database'], $notification->via(new AnonymousNotifiable()));
    }

    public function test_user_followed_notification_to_array()
    {
        $actor = User::factory()->create([
            'name' => 'Bob Wilson',
            'username' => 'bob.wilson',
            'profile_url' => '/profile/bob.wilson',
        ]);
        $notifiable = User::factory()->create();

        $notification = new UserFollowed($actor);
        $array = $notification->toArray($notifiable);

        $this->assertEquals('user_followed', $array['type']);
        $this->assertEquals($actor->id, $array['actor_id']);
        $this->assertEquals($actor->name, $array['actor_name']);
        $this->assertEquals($actor->username, $array['actor_username']);
        $this->assertEquals($actor->profile_url, $array['actor_profile_url']);
        $this->assertEquals($actor->name . ' started following you', $array['message']);
    }

    public function test_notifications_are_queueable()
    {
        $actor = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create();

        $postLikedNotification = new PostLiked($actor, $post);
        $postCommentedNotification = new PostCommented($actor, $post, $comment);
        $userFollowedNotification = new UserFollowed($actor);

        $this->assertTrue(in_array('Illuminate\Bus\Queueable', class_uses($postLikedNotification)));
        $this->assertTrue(in_array('Illuminate\Bus\Queueable', class_uses($postCommentedNotification)));
        $this->assertTrue(in_array('Illuminate\Bus\Queueable', class_uses($userFollowedNotification)));
    }

    public function test_notifications_extend_base_notification()
    {
        $actor = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create();

        $postLikedNotification = new PostLiked($actor, $post);
        $postCommentedNotification = new PostCommented($actor, $post, $comment);
        $userFollowedNotification = new UserFollowed($actor);

        $this->assertInstanceOf('Illuminate\Notifications\Notification', $postLikedNotification);
        $this->assertInstanceOf('Illuminate\Notifications\Notification', $postCommentedNotification);
        $this->assertInstanceOf('Illuminate\Notifications\Notification', $userFollowedNotification);
    }

    public function test_notification_handles_null_content()
    {
        $actor = User::factory()->create(['name' => 'John Doe']);
        $post = Post::factory()->create(['content' => 'Test post content']);
        $comment = Comment::factory()->create(['content' => 'Test comment content']);

        $postLikedNotification = new PostLiked($actor, $post);
        $postCommentedNotification = new PostCommented($actor, $post, $comment);

        $postLikedArray = $postLikedNotification->toArray(new AnonymousNotifiable());
        $postCommentedArray = $postCommentedNotification->toArray(new AnonymousNotifiable());

        $this->assertEquals('Test post content', $postLikedArray['post_content']);
        $this->assertEquals('Test post content', $postCommentedArray['post_content']);
        $this->assertEquals('Test comment content', $postCommentedArray['comment_content']);
    }
}

