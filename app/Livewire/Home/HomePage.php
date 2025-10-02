<?php

namespace App\Livewire\Home;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Livewire\Component;

class HomePage extends Component
{
    public $likedPosts = [];
    public $showComments = [];
    public $newComments = [];
    public $comments = [];
    public $posts = [];
    public $page = 1;
    public $hasMorePosts = true;
    public $loading = false;

    public function mount()
    {
        // Initialize liked posts for current user
        $this->likedPosts = Post::whereHas('likes', function($query) {
            $query->where('user_id', auth()->id());
        })->pluck('id')->toArray();

        // Initialize comments visibility
        $this->showComments = [];
        $this->newComments = [];

        // Load initial posts
        $this->loadPosts();
    }

    public function toggleLike($postId)
    {
        $post = Post::findOrFail($postId);

        if (in_array($postId, $this->likedPosts)) {
            $post->unlike(auth()->user());
            $this->likedPosts = array_diff($this->likedPosts, [$postId]);
        } else {
            $post->like(auth()->user());
            $this->likedPosts[] = $postId;
        }
    }

    public function toggleComments($postId)
    {
        if (in_array($postId, $this->showComments)) {
            $this->showComments = array_diff($this->showComments, [$postId]);
        } else {
            $this->showComments[] = $postId;
            $this->loadComments($postId);
        }
    }

    public function loadComments($postId)
    {
        $this->comments[$postId] = Comment::where('post_id', $postId)
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();
    }

    public function addComment($postId)
    {
        $this->validate([
            "newComments.{$postId}" => 'required|string|max:500',
        ]);

        $post = Post::findOrFail($postId);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $this->newComments[$postId],
        ]);

        $post->increment('comments_count');

        // Clear the input
        $this->newComments[$postId] = '';

        // Reload comments for this post
        $this->loadComments($postId);

        // Show comments if not already shown
        if (!in_array($postId, $this->showComments)) {
            $this->showComments[] = $postId;
        }
    }

    public function isLiked($postId)
    {
        return in_array($postId, $this->likedPosts);
    }

    public function loadPosts()
    {
        if ($this->loading || !$this->hasMorePosts) {
            return;
        }

        $this->loading = true;

        $newPosts = Post::with('user')
            ->orderBy('created_at', 'desc')
            ->skip(($this->page - 1) * 10)
            ->limit(10)
            ->get();

        if ($newPosts->count() < 10) {
            $this->hasMorePosts = false;
        }

        if ($this->page === 1) {
            $this->posts = $newPosts;
        } else {
            $this->posts = $this->posts->concat($newPosts);
        }

        $this->page++;
        $this->loading = false;
    }

    public function loadMore()
    {
        $this->loadPosts();
    }

    public function render()
    {
        // Fetch suggested users (excluding current user)
        $suggestedUsers = User::where('id', '!=', auth()->id())
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('livewire.home.home-page', [
            'suggestedUsers' => $suggestedUsers,
        ]);
    }
}
