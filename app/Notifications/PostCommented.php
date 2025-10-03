<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostCommented extends Notification
{
    use Queueable;

    public function __construct(public User $actor, public Post $post, public Comment $comment)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'post_commented',
            'actor_id' => $this->actor->id,
            'actor_name' => $this->actor->name,
            'actor_username' => $this->actor->username,
            'actor_profile_url' => $this->actor->profile_url,
            'post_id' => $this->post->id,
            'post_content' => (string) ($this->post->content ?? ''),
            'comment_id' => $this->comment->id,
            'comment_content' => (string) ($this->comment->content ?? ''),
            'message' => $this->actor->name . ' commented on your post',
        ];
    }
}


