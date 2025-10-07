<?php

namespace App\Livewire\Home;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Notifications\PostCommented;
use App\Notifications\PostLiked;
use App\Notifications\UserFollowed;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HomePage extends Component
{
    public $likedPosts = [];
    public $savedPosts = [];
    public $showComments = [];
    public $newComments = [];
    public $comments = [];
    public $posts = [];
    public $page = 1;
    public $hasMorePosts = true;
    public $loading = false;

    // Follow functionality
    public $followingUsers = [];

    // Delete post functionality
    public $postToDelete = null;

    public function mount()
    {
        $userId = auth()->id();

        // Optimize: Use direct table queries instead of whereHas to avoid N+1
        $this->likedPosts = DB::table('post_likes')
            ->where('user_id', $userId)
            ->pluck('post_id')
            ->toArray();

        $this->savedPosts = DB::table('saved_posts')
            ->where('user_id', $userId)
            ->pluck('post_id')
            ->toArray();

        // Initialize follow state
        $this->followingUsers = DB::table('follows')
            ->where('follower_id', $userId)
            ->pluck('following_id')
            ->toArray();

        // Initialize comments visibility
        $this->showComments = [];
        $this->newComments = [];

        // Load initial posts
        $this->loadPosts();
    }

    public function toggleLike($postId)
    {
        $user = auth()->user();
        if (!$user) {
            return;
        }

        $post = Post::findOrFail($postId);

        // Check the actual database state instead of relying on the array
        $isCurrentlyLiked = $post->isLikedBy($user);

        if ($isCurrentlyLiked) {
            // Post is currently liked, so unlike it
            $post->unlike($user);
            // Remove from likedPosts array
            $this->likedPosts = array_values(array_diff($this->likedPosts, [$postId]));
        } else {
            // Post is not currently liked, so like it
            $post->like($user);
            // Add to likedPosts array
            $this->likedPosts[] = $postId;
            // Send notification if not self-liking
            if ($post->user_id !== $user->id) {
                $post->user->notify(new PostLiked($user, $post));
            }
        }

        // Refresh the post in the posts collection to update the count
        $this->refreshPostInCollection($postId);
    }

    private function refreshPostInCollection($postId)
    {
        // Find and refresh the post in the posts collection
        foreach ($this->posts as $index => $post) {
            if ($post->id == $postId) {
                // Get fresh data from database
                $freshPost = Post::with('user')->find($postId);
                if ($freshPost) {
                    $this->posts[$index] = $freshPost;
                }
                break;
            }
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
        if ($post->user_id !== auth()->id()) {
            $post->user->notify(new PostCommented(auth()->user(), $post, $comment));
        }

        // Clear the input
        $this->newComments[$postId] = '';

        // Reload comments for this post
        $this->loadComments($postId);

        // Show comments if not already shown
        if (!in_array($postId, $this->showComments)) {
            $this->showComments[] = $postId;
        }

        // Refresh the post in the posts collection to update the count
        $this->refreshPostInCollection($postId);
    }

    public function isLiked($postId)
    {
        return in_array($postId, $this->likedPosts);
    }

    public function toggleSave($postId)
    {
        $post = Post::findOrFail($postId);

        if (in_array($postId, $this->savedPosts)) {
            $post->unsaveBy(auth()->user());
            $this->savedPosts = array_diff($this->savedPosts, [$postId]);
        } else {
            $post->saveBy(auth()->user());
            $this->savedPosts[] = $postId;
        }
    }

    public function isSaved($postId)
    {
        return in_array($postId, $this->savedPosts);
    }

    public function toggleFollow($userId)
    {
        $currentUser = auth()->user();

        if (!$currentUser) {
            return redirect()->route('login');
        }

        if ($currentUser->id === $userId) {
            return;
        }

        $user = User::findOrFail($userId);

        if (in_array($userId, $this->followingUsers)) {
            // Unfollow
            $currentUser->unfollow($user);
            $this->followingUsers = array_values(array_diff($this->followingUsers, [$userId]));
        } else {
            // Follow
            $currentUser->follow($user);
            $this->followingUsers[] = $userId;
            // Send notification
            $user->notify(new UserFollowed($currentUser));
        }
    }

    public function isFollowing($userId)
    {
        return in_array($userId, $this->followingUsers);
    }

    public function loadPosts()
    {
        if ($this->loading || !$this->hasMorePosts) {
            return;
        }

        $this->loading = true;

        // Optimize: Use scope for efficient feed loading with better performance
        $newPosts = Post::with(['user'])
            ->forFeed(30) // Use the new scope
            ->skip(($this->page - 1) * 15) // Load more posts per batch for better UX
            ->limit(15)
            ->get();

        if ($newPosts->count() < 15) {
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

            // Remove from liked posts if it was liked
            $this->likedPosts = array_values(array_diff($this->likedPosts, [$this->postToDelete]));

            // Remove from saved posts if it was saved
            $this->savedPosts = array_values(array_diff($this->savedPosts, [$this->postToDelete]));

            // Remove from comments if it was showing comments
            $this->showComments = array_values(array_diff($this->showComments, [$this->postToDelete]));

            // Remove comments data
            unset($this->comments[$this->postToDelete]);
            unset($this->newComments[$this->postToDelete]);

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
        // Optimize: Use more efficient user suggestions
        $suggestedUsers = User::where('id', '!=', auth()->id())
            ->where('created_at', '>', now()->subDays(90)) // Recent users
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.home.home-page', [
            'suggestedUsers' => $suggestedUsers,
        ]);
    }
}
