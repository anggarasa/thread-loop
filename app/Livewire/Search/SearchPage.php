<?php

namespace App\Livewire\Search;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Notifications\UserFollowed;
use App\Notifications\PostCommented;
use App\Notifications\PostLiked;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SearchPage extends Component
{
    public $search = '';
    public $activeTab = 'posts';
    public $sortBy = 'recent';
    public $showFilters = false;
    public $dateFilter = 'all';
    public $mediaFilter = 'all';
    public $showSuggestions = false;

    // Infinite scroll properties
    public $posts = [];
    public $users = [];
    public $page = 1;
    public $hasMorePosts = true;
    public $hasMoreUsers = true;
    public $loading = false;

    // Like and save functionality
    public $likedPosts = [];
    public $savedPosts = [];
    public $showComments = [];
    public $newComments = [];
    public $comments = [];

    // Follow functionality
    public $followingUsers = [];

    // Delete post functionality
    public $postToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'activeTab' => ['except' => 'posts'],
        'sortBy' => ['except' => 'recent'],
    ];

    public function mount()
    {
        $userId = auth()->id();
        if ($userId) {
            // Initialize like and save state
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
        }

        // Initialize comments visibility
        $this->showComments = [];
        $this->newComments = [];

        $this->loadInitialData();
    }

    public function updatedSearch()
    {
        $this->resetSearchData();
        $this->showSuggestions = !empty($this->search) && strlen($this->search) < 3;
        if (!empty($this->search)) {
            $this->loadInitialData();
        }
    }

    public function updatedActiveTab()
    {
        $this->resetSearchData();
        if (!empty($this->search)) {
            if ($this->activeTab === 'posts') {
                $this->loadInitialPosts();
            } else {
                $this->loadInitialUsers();
            }
        }
    }

    public function updatedSortBy()
    {
        $this->resetSearchData();
        if (!empty($this->search)) {
            if ($this->activeTab === 'posts') {
                $this->loadInitialPosts();
            } else {
                $this->loadInitialUsers();
            }
        }
    }

    public function updatedDateFilter()
    {
        $this->resetSearchData();
        if (!empty($this->search)) {
            if ($this->activeTab === 'posts') {
                $this->loadInitialPosts();
            } else {
                $this->loadInitialUsers();
            }
        }
    }

    public function updatedMediaFilter()
    {
        $this->resetSearchData();
        if (!empty($this->search)) {
            $this->loadInitialPosts(); // Media filter only applies to posts
        }
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->showSuggestions = false;
        $this->resetSearchData();
    }

    public function selectSuggestion($suggestion)
    {
        $this->search = $suggestion;
        $this->showSuggestions = false;
        $this->resetSearchData();
        $this->loadInitialData();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function clearFilters()
    {
        $this->dateFilter = 'all';
        $this->mediaFilter = 'all';
        $this->resetSearchData();
        $this->loadInitialData();
    }

    private function resetSearchData()
    {
        $this->posts = collect();
        $this->users = collect();
        $this->page = 1;
        $this->hasMorePosts = true;
        $this->hasMoreUsers = true;
        $this->loading = false;
    }

    private function loadInitialData()
    {
        if (empty($this->search)) {
            return;
        }

        $this->page = 1;
        $this->hasMorePosts = true;
        $this->hasMoreUsers = true;
        $this->loading = false;

        // Load initial data for both tabs without incrementing page
        $this->loadInitialPosts();
        $this->loadInitialUsers();
    }

    public function loadMorePosts()
    {
        $this->loadPosts();
    }

    public function loadMoreUsers()
    {
        $this->loadUsers();
    }

    public function loadMore()
    {
        if ($this->activeTab === 'posts') {
            $this->loadMorePosts();
        } else {
            $this->loadMoreUsers();
        }
    }

    private function loadInitialPosts()
    {
        if (empty($this->search)) {
            return;
        }

        $query = Post::with('user')
            ->where('content', 'like', '%' . $this->search . '%');

        // Apply media filter
        if ($this->mediaFilter !== 'all') {
            if ($this->mediaFilter === 'text') {
                $query->whereNull('media_type');
            } else {
                $query->where('media_type', $this->mediaFilter);
            }
        }

        // Apply date filter
        if ($this->dateFilter !== 'all') {
            $dateFilter = match($this->dateFilter) {
                'today' => now()->startOfDay(),
                'week' => now()->subWeek(),
                'month' => now()->subMonth(),
                'year' => now()->subYear(),
                default => null
            };

            if ($dateFilter) {
                $query->where('created_at', '>=', $dateFilter);
            }
        }

        // Apply sorting
        $query->orderBy($this->getSortColumn(), $this->getSortDirection());

        $newPosts = $query->skip(($this->page - 1) * 15)
            ->limit(15)
            ->get();

        if ($newPosts->count() < 15) {
            $this->hasMorePosts = false;
        }

        $this->posts = $newPosts;
    }

    private function loadInitialUsers()
    {
        if (empty($this->search)) {
            return;
        }

        $query = User::where(function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
              ->orWhere('username', 'like', '%' . $this->search . '%')
              ->orWhere('email', 'like', '%' . $this->search . '%');
        });

        // Apply date filter
        if ($this->dateFilter !== 'all') {
            $dateFilter = match($this->dateFilter) {
                'today' => now()->startOfDay(),
                'week' => now()->subWeek(),
                'month' => now()->subMonth(),
                'year' => now()->subYear(),
                default => null
            };

            if ($dateFilter) {
                $query->where('created_at', '>=', $dateFilter);
            }
        }

        // Apply sorting for users specifically
        $sortColumn = match($this->sortBy) {
            'recent' => 'created_at',
            'popular' => 'created_at', // Users don't have likes_count, use created_at
            'name' => 'name',
            'username' => 'username',
            default => 'created_at'
        };

        $sortDirection = match($this->sortBy) {
            'recent' => 'desc',
            'popular' => 'desc',
            'name' => 'asc',
            'username' => 'asc',
            default => 'desc'
        };

        $query->orderBy($sortColumn, $sortDirection);

        $newUsers = $query->skip(($this->page - 1) * 10)
            ->limit(10)
            ->get();

        if ($newUsers->count() < 10) {
            $this->hasMoreUsers = false;
        }

        $this->users = $newUsers;
    }

    private function loadPosts()
    {
        if ($this->loading || !$this->hasMorePosts || empty($this->search)) {
            return;
        }

        $this->loading = true;

        $query = Post::with('user')
            ->where('content', 'like', '%' . $this->search . '%');

        // Apply media filter
        if ($this->mediaFilter !== 'all') {
            if ($this->mediaFilter === 'text') {
                $query->whereNull('media_type');
            } else {
                $query->where('media_type', $this->mediaFilter);
            }
        }

        // Apply date filter
        if ($this->dateFilter !== 'all') {
            $dateFilter = match($this->dateFilter) {
                'today' => now()->startOfDay(),
                'week' => now()->subWeek(),
                'month' => now()->subMonth(),
                'year' => now()->subYear(),
                default => null
            };

            if ($dateFilter) {
                $query->where('created_at', '>=', $dateFilter);
            }
        }

        // Apply sorting
        $query->orderBy($this->getSortColumn(), $this->getSortDirection());

        $newPosts = $query->skip(($this->page - 1) * 15)
            ->limit(15)
            ->get();

        if ($newPosts->count() < 15) {
            $this->hasMorePosts = false;
        }

        if ($this->page === 1) {
            $this->posts = $newPosts;
        } else {
            $this->posts = collect($this->posts)->concat($newPosts);
        }

        $this->page++;
        $this->loading = false;
    }

    private function loadUsers()
    {
        if ($this->loading || !$this->hasMoreUsers || empty($this->search)) {
            return;
        }

        $this->loading = true;

        $query = User::where(function ($q) {
            $q->where('name', 'like', '%' . $this->search . '%')
              ->orWhere('username', 'like', '%' . $this->search . '%')
              ->orWhere('email', 'like', '%' . $this->search . '%');
        });

        // Apply date filter
        if ($this->dateFilter !== 'all') {
            $dateFilter = match($this->dateFilter) {
                'today' => now()->startOfDay(),
                'week' => now()->subWeek(),
                'month' => now()->subMonth(),
                'year' => now()->subYear(),
                default => null
            };

            if ($dateFilter) {
                $query->where('created_at', '>=', $dateFilter);
            }
        }

        // Apply sorting for users specifically
        $sortColumn = match($this->sortBy) {
            'recent' => 'created_at',
            'popular' => 'created_at', // Users don't have likes_count, use created_at
            'name' => 'name',
            'username' => 'username',
            default => 'created_at'
        };

        $sortDirection = match($this->sortBy) {
            'recent' => 'desc',
            'popular' => 'desc',
            'name' => 'asc',
            'username' => 'asc',
            default => 'desc'
        };

        $query->orderBy($sortColumn, $sortDirection);

        $newUsers = $query->skip(($this->page - 1) * 10)
            ->limit(10)
            ->get();

        if ($newUsers->count() < 10) {
            $this->hasMoreUsers = false;
        }

        if ($this->page === 1) {
            $this->users = $newUsers;
        } else {
            $this->users = collect($this->users)->concat($newUsers);
        }

        $this->page++;
        $this->loading = false;
    }


    private function getSortColumn()
    {
        if ($this->activeTab === 'posts') {
            return match($this->sortBy) {
                'recent' => 'created_at',
                'popular' => 'likes_count',
                default => 'created_at'
            };
        } else {
            return match($this->sortBy) {
                'recent' => 'created_at',
                'popular' => 'created_at', // Users don't have likes_count, use created_at
                'name' => 'name',
                'username' => 'username',
                default => 'created_at'
            };
        }
    }

    private function getSortDirection()
    {
        return match($this->sortBy) {
            'recent' => 'desc',
            'popular' => 'desc',
            'name' => 'asc',
            'username' => 'asc',
            default => 'desc'
        };
    }

    public function getSuggestionsProperty()
    {
        if (empty($this->search) || strlen($this->search) < 2) {
            return collect();
        }

        $suggestions = collect();

        // Get recent posts with matching content
        $recentPosts = Post::where('content', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($recentPosts as $post) {
            $suggestions->push([
                'type' => 'post',
                'text' => \Illuminate\Support\Str::limit($post->content, 50),
                'value' => $post->content,
                'icon' => 'document-text'
            ]);
        }

        // Get users with matching names or usernames
        $matchingUsers = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('username', 'like', '%' . $this->search . '%')
            ->limit(3)
            ->get();

        foreach ($matchingUsers as $user) {
            $suggestions->push([
                'type' => 'user',
                'text' => $user->name . ' (@' . $user->username . ')',
                'value' => $user->username,
                'icon' => 'user'
            ]);
        }

        return $suggestions->take(5);
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
        // Find and replace the specific post in the collection preserving objects
        $this->posts = collect($this->posts)->map(function ($post) use ($postId) {
            // Check ID on either object or array to be safe, though expecting object
            $currentId = is_array($post) ? $post['id'] : $post->id;

            if ($currentId == $postId) {
                // Get fresh data from database
                return Post::with('user')->find($postId) ?? $post;
            }

            return $post;
        });
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
            $this->posts = collect($this->posts)->reject(function ($p) {
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
        return view('livewire.search.search-page', [
            'posts' => $this->activeTab === 'posts' ? $this->posts : collect(),
            'users' => $this->activeTab === 'users' ? $this->users : collect(),
            'suggestions' => $this->suggestions,
        ]);
    }
}
