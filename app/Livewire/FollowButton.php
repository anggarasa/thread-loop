<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class FollowButton extends Component
{
    public User $user;
    public bool $isFollowing = false;
    public int $followersCount = 0;

    public function mount(User $user)
    {
        $this->user = $user;
        $currentUser = auth()->user();
        $this->isFollowing = $currentUser ? $currentUser->follows($user) : false;
        $this->followersCount = $user->followersCount();
    }

    public function toggleFollow()
    {
        $currentUser = auth()->user();

        if (!$currentUser) {
            return redirect()->route('login');
        }

        if ($currentUser->id === $this->user->id) {
            return;
        }

        if ($this->isFollowing) {
            $currentUser->unfollow($this->user);
            $this->isFollowing = false;
        } else {
            $currentUser->follow($this->user);
            $this->isFollowing = true;
        }

        $this->followersCount = $this->user->fresh()->followersCount();
    }

    public function render()
    {
        return view('livewire.follow-button');
    }
}
