<?php

namespace App\Livewire\Notifications;

use Illuminate\Notifications\DatabaseNotification;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationList extends Component
{
    use WithPagination;

    public $unreadCount = 0;

    public function mount()
    {
        $this->refreshCounts();
    }

    #[On('notification-read')]
    public function refreshCounts(): void
    {
        $this->unreadCount = auth()->user()->unreadNotifications()->count();
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        $this->refreshCounts();
    }

    public function markAsRead(string $id): void
    {
        /** @var DatabaseNotification|null $notification */
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
            $this->dispatch('notification-read');
        }
    }

    public function view(string $id)
    {
        /** @var DatabaseNotification|null $notification */
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if (!$notification) {
            return null;
        }

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
            $this->dispatch('notification-read');
        }

        $data = $notification->data ?? [];
        if (!empty($data['post_id'])) {
            return $this->redirect(route('posts.show', $data['post_id']), navigate: true);
        }

        if (!empty($data['actor_username'])) {
            return $this->redirect(route('profile.show', $data['actor_username']), navigate: true);
        }

        return null;
    }

    public function render()
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderByRaw('read_at IS NULL DESC')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.notifications.list', [
            'notifications' => $notifications,
        ]);
    }
}


