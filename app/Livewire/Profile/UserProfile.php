<?php

namespace App\Livewire\Profile;

use App\Models\User;
use App\Models\Post;
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

    // Delete post functionality
    public $postToDelete = null;

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

    public function deletePost($postId)
    {
        $post = Post::findOrFail($postId);

        // Check if user is authorized to delete this post
        if (!$post->canBeDeletedBy(auth()->user())) {
            session()->flash('error', 'You are not authorized to delete this post.');
            return;
        }

        $this->postToDelete = $postId;
    }

    public function confirmDeletePost()
    {
        if (!$this->postToDelete) {
            return;
        }

        try {
            $post = Post::findOrFail($this->postToDelete);

            // Double check authorization
            if (!$post->canBeDeletedBy(auth()->user())) {
                session()->flash('error', 'You are not authorized to delete this post.');
                $this->postToDelete = null;
                return;
            }

            // Delete the post
            $post->delete();

            // Remove from posts collection
            $this->posts = $this->posts->reject(function ($p) {
                return $p->id == $this->postToDelete;
            });

            // Remove from saved posts if it was saved
            if ($this->savedPosts) {
                $this->savedPosts = $this->savedPosts->reject(function ($p) {
                    return $p->id == $this->postToDelete;
                });
            }

            // Remove from liked posts if it was liked
            if ($this->likedPosts) {
                $this->likedPosts = $this->likedPosts->reject(function ($p) {
                    return $p->id == $this->postToDelete;
                });
            }

            $this->postToDelete = null;
            session()->flash('success', 'Post deleted successfully.');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete post. Please try again.');
            $this->postToDelete = null;
        }
    }

    public function cancelDeletePost()
    {
        $this->postToDelete = null;
    }

    public function render()
    {
        return view('livewire.profile.user-profile')->layout('components.layouts.app');
    }
}
