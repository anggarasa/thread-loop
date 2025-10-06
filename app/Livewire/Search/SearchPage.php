<?php

namespace App\Livewire\Search;

use App\Models\Post;
use App\Models\User;
use App\Notifications\UserFollowed;
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
        // Initialize follow state
        $userId = auth()->id();
        if ($userId) {
            $this->followingUsers = \Illuminate\Support\Facades\DB::table('follows')
                ->where('follower_id', $userId)
                ->pluck('following_id')
                ->toArray();
        }

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

        $newPosts = $query->skip(($this->page - 1) * 12)
            ->limit(12)
            ->get();

        if ($newPosts->count() < 12) {
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

        $newPosts = $query->skip(($this->page - 1) * 12)
            ->limit(12)
            ->get();

        if ($newPosts->count() < 12) {
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
