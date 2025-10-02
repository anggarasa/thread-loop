<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Livewire\Component;

class UserProfile extends Component
{
    public User $user;
    public $posts;
    public $followersCount;
    public $followingCount;

    public function mount($username)
    {
        $this->user = User::where('username', $username)->firstOrFail();
        $this->posts = $this->user->posts()->latest()->get();
        $this->followersCount = $this->user->followersCount();
        $this->followingCount = $this->user->followingCount();
    }

    public function render()
    {
        return view('livewire.profile.user-profile')->layout('components.layouts.app');
    }
}
