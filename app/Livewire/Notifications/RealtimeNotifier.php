<?php

namespace App\Livewire\Notifications;

use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;

class RealtimeNotifier extends Component
{
    public bool $show = false;
    public string $message = '';
    public ?string $url = null;
    public ?string $lastSeenAt = null;

    public function mount(): void
    {
        if (!auth()->check()) {
            return;
        }

        $latest = auth()->user()->notifications()->latest('created_at')->first();
        $this->lastSeenAt = optional($latest)->created_at?->toDateTimeString() ?? now()->toDateTimeString();
    }

    public function check(): void
    {
        if (!auth()->check()) {
            return;
        }

        /** @var DatabaseNotification|null $notification */
        $notification = auth()->user()
            ->unreadNotifications()
            ->when($this->lastSeenAt, function ($q) {
                $q->where('created_at', '>', $this->lastSeenAt);
            })
            ->latest('created_at')
            ->first();

        if ($notification) {
            $data = $notification->data ?? [];
            $this->message = (string)($data['message'] ?? 'You have a new notification');
            $this->url = null;

            if (!empty($data['post_id'])) {
                $this->url = route('posts.show', $data['post_id']);
            } elseif (!empty($data['actor_username'])) {
                $this->url = route('profile.show', $data['actor_username']);
            } else {
                $this->url = route('notifications');
            }

            $this->show = true;
            $this->lastSeenAt = $notification->created_at->toDateTimeString();
        }
    }

    public function render()
    {
        return view('livewire.notifications.realtime-notifier');
    }
}


