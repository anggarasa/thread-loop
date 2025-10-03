<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostLinkCopied extends Notification
{
    use Queueable;

    public function __construct(public User $actor, public Post $post)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'post_link_copied',
            'actor_id' => $this->actor->id,
            'actor_name' => $this->actor->name,
            'actor_username' => $this->actor->username,
            'actor_profile_url' => $this->actor->profile_url,
            'post_id' => $this->post->id,
            'post_content' => (string) ($this->post->content ?? ''),
            'message' => $this->actor->name . ' copied the link to your post',
        ];
    }
}


