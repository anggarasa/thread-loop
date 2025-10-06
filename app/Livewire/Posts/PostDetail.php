<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Models\User;
use App\Notifications\PostCommented;
use App\Notifications\PostLiked;
use App\Notifications\UserFollowed;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PostDetail extends Component
{
    use WithPagination;

    public Post $post;
    public $newComment = '';
    public $showComments = false;
    public $isLiked = false;
    public $likesCount = 0;
    public $isSaved = false;

    // Follow functionality
    public $followingUsers = [];

    // Delete post functionality
    public $showDeleteModal = false;

    public function mount(Post $post)
    {
        $this->post = $post->load(['user', 'comments.user']);
        $this->likesCount = $this->post->likes_count;
        $this->isLiked = $this->post->isLikedBy(auth()->user());
        $this->isSaved = $this->post->isSavedBy(auth()->user());

        // Initialize follow state
        $userId = auth()->id();
        if ($userId) {
            $this->followingUsers = DB::table('follows')
                ->where('follower_id', $userId)
                ->pluck('following_id')
                ->toArray();
        }
    }

    public function toggleLike()
    {
        if ($this->isLiked) {
            $this->post->unlike(auth()->user());
            $this->likesCount--;
        } else {
            $this->post->like(auth()->user());
            $this->likesCount++;
            // Notify post owner of like (avoid self-notification)
            if ($this->post->user_id !== auth()->id()) {
                $this->post->user->notify(new PostLiked(auth()->user(), $this->post));
            }
        }
        $this->isLiked = !$this->isLiked;
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required|string|max:500',
        ]);

        $comment = $this->post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $this->newComment,
        ]);

        $this->post->increment('comments_count');
        // Notify post owner of comment (avoid self-notification)
        if ($this->post->user_id !== auth()->id()) {
            $this->post->user->notify(new PostCommented(auth()->user(), $this->post, $comment));
        }
        $this->newComment = '';
        $this->showComments = true;

        // Refresh the post to get updated comments_count
        $this->post->refresh();
    }

    public function toggleComments()
    {
        $this->showComments = !$this->showComments;
    }

    public function toggleSave()
    {
        if ($this->isSaved) {
            $this->post->unsaveBy(auth()->user());
        } else {
            $this->post->saveBy(auth()->user());
        }
        $this->isSaved = !$this->isSaved;
    }

    public function goBack()
    {
        return redirect()->back();
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

    public function deletePost()
    {
        // Check if user is authorized to delete this post
        if (!$this->post->canBeDeletedBy(auth()->user())) {
            session()->flash('error', 'You are not authorized to delete this post.');
            return;
        }

        $this->showDeleteModal = true;
    }

    public function confirmDeletePost()
    {
        // Double check authorization
        if (!$this->post->canBeDeletedBy(auth()->user())) {
            session()->flash('error', 'You are not authorized to delete this post.');
            $this->showDeleteModal = false;
            return;
        }

        try {
            // Delete the post
            $this->post->delete();

            session()->flash('success', 'Post deleted successfully.');

            // Redirect to home page
            return redirect()->route('homePage');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete post. Please try again.');
            $this->showDeleteModal = false;
        }
    }

    public function cancelDeletePost()
    {
        $this->showDeleteModal = false;
    }

    public function render()
    {
        $comments = $this->post->comments()
            ->with('user')
            ->latest()
            ->paginate(10);

        $suggestedPosts = Post::with('user')
            ->where('id', '!=', $this->post->id)
            ->where('user_id', '!=', $this->post->user_id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('livewire.posts.post-detail', [
            'comments' => $comments,
            'suggestedPosts' => $suggestedPosts,
        ]);
    }
}
