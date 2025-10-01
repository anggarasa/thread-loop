<?php

namespace App\Livewire\Search;

use App\Models\Post;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class SearchPage extends Component
{
    use WithPagination;

    public $search = '';
    public $activeTab = 'posts';
    public $sortBy = 'recent';
    public $showFilters = false;
    public $dateFilter = 'all';
    public $mediaFilter = 'all';
    public $showSuggestions = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'activeTab' => ['except' => 'posts'],
        'sortBy' => ['except' => 'recent'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
        $this->showSuggestions = !empty($this->search) && strlen($this->search) < 3;
    }

    public function updatedActiveTab()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->showSuggestions = false;
        $this->resetPage();
    }

    public function selectSuggestion($suggestion)
    {
        $this->search = $suggestion;
        $this->showSuggestions = false;
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function clearFilters()
    {
        $this->dateFilter = 'all';
        $this->mediaFilter = 'all';
        $this->resetPage();
    }

    public function getPostsProperty()
    {
        if (empty($this->search) || $this->activeTab !== 'posts') {
            return collect();
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

        return $query->paginate(12);
    }

    public function getUsersProperty()
    {
        if (empty($this->search) || $this->activeTab !== 'users') {
            return collect();
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

        // Apply sorting
        $query->orderBy($this->getSortColumn(), $this->getSortDirection());

        return $query->paginate(10);
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

    public function render()
    {
        return view('livewire.search.search-page', [
            'posts' => $this->activeTab === 'posts' ? $this->posts : collect(),
            'users' => $this->activeTab === 'users' ? $this->users : collect(),
            'suggestions' => $this->suggestions,
        ]);
    }
}
