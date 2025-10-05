<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Livewire\Component;

class UserProfile extends Component
{
    public User $user;
    public $posts;
    public $savedPosts;
    public $likedPosts;
    public $followersCount;
    public $followingCount;
    public $activeTab = 'posts'; // 'posts', 'saved', or 'liked'

    public function mount($username)
    {
        $this->user = User::where('username', $username)->firstOrFail();
        $this->posts = $this->user->posts()->latest()->get();
        $this->followersCount = $this->user->followersCount();
        $this->followingCount = $this->user->followingCount();

        // Only load saved posts and liked posts if viewing own profile
        if (auth()->check() && auth()->id() === $this->user->id) {
            $this->savedPosts = $this->user->savedPostsWithPost()
                ->with('post.user')
                ->latest()
                ->get()
                ->pluck('post');

            $this->likedPosts = $this->user->likedPosts()
                ->with('user')
                ->latest()
                ->get();
        } else {
            $this->savedPosts = collect();
            $this->likedPosts = collect();
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.profile.user-profile')->layout('components.layouts.app');
    }
}
