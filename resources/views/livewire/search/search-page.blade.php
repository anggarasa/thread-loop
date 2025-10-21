<div class="min-h-screen" data-page="search">
    <div class="mx-auto max-w-7xl px-4 py-6 lg:px-6">
        <!-- Search Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-zinc-900 dark:text-white mb-2">Search</h1>
            <p class="text-zinc-600 dark:text-zinc-400">Find posts and people on Thread Loop</p>
        </div>

        <!-- Search Bar -->
        <div class="relative mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    autofocus="true"
                    placeholder="Search posts, users, or anything..."
                    class="w-full pl-12 pr-12 py-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-lg"
                    wire:loading.attr="disabled"
                >
                @if($search)
                    <button
                        wire:click="clearSearch"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif
            </div>

            <!-- Search Suggestions Dropdown -->
            @if($showSuggestions && $suggestions->count() > 0)
                <div class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg z-50 max-h-80 overflow-y-auto">
                    <div class="p-2">
                        <div class="text-xs font-medium text-zinc-500 dark:text-zinc-400 px-3 py-2 uppercase tracking-wide">
                            Suggestions
                        </div>
                        @foreach($suggestions as $suggestion)
                            <button
                                wire:click="selectSuggestion('{{ $suggestion['value'] }}')"
                                class="w-full flex items-center space-x-3 px-3 py-3 text-left hover:bg-zinc-50 dark:hover:bg-zinc-700 rounded-lg transition-colors group"
                            >
                                <div class="flex-shrink-0">
                                    @if($suggestion['icon'] === 'user')
                                        <svg class="h-5 w-5 text-zinc-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-zinc-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-zinc-900 dark:text-white truncate">
                                        {{ $suggestion['text'] }}
                                    </p>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ ucfirst($suggestion['type']) }}
                                    </p>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @if($search)
            <!-- Search Controls -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-4 sm:space-y-0">
                <!-- Tabs -->
                <div class="flex space-x-1 bg-zinc-100 dark:bg-zinc-800 p-1 rounded-xl">
                    <button
                        wire:click="$set('activeTab', 'posts')"
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ $activeTab === 'posts' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}"
                    >
                        Posts
                        @if($posts->count() > 0)
                            <span class="ml-2 px-2 py-0.5 text-xs bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 rounded-full">
                                {{ $posts->count() }}
                            </span>
                        @endif
                    </button>
                    <button
                        wire:click="$set('activeTab', 'users')"
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 {{ $activeTab === 'users' ? 'bg-white dark:bg-zinc-700 text-zinc-900 dark:text-white shadow-sm' : 'text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}"
                    >
                        Users
                        @if($users->count() > 0)
                            <span class="ml-2 px-2 py-0.5 text-xs bg-green-100 dark:bg-green-900 text-green-600 dark:text-green-300 rounded-full">
                                {{ $users->count() }}
                            </span>
                        @endif
                    </button>
                </div>

                <!-- Controls -->
                <div class="flex items-center space-x-3">
                    <!-- Sort Dropdown -->
                    <div class="relative">
                        <select
                            wire:model.live="sortBy"
                            class="appearance-none bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                            <option value="recent">Most Recent</option>
                            <option value="popular">Most Popular</option>
                            @if($activeTab === 'users')
                                <option value="name">Name A-Z</option>
                                <option value="username">Username A-Z</option>
                            @endif
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="h-4 w-4 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Filter Toggle -->
                    <button
                        wire:click="toggleFilters"
                        class="flex items-center space-x-2 px-3 py-2 text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-lg transition-colors"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span>Filters</span>
                    </button>
                </div>
            </div>

            <!-- Filters Panel -->
            @if($showFilters)
                <div class="mb-6 p-4 bg-zinc-50 dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-medium text-zinc-900 dark:text-white">Filters</h3>
                        <button
                            wire:click="clearFilters"
                            class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300"
                        >
                            Clear all
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Date Filter -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Date</label>
                            <select
                                wire:model.live="dateFilter"
                                class="w-full bg-white dark:bg-zinc-700 border border-zinc-200 dark:border-zinc-600 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="all">All time</option>
                                <option value="today">Today</option>
                                <option value="week">This week</option>
                                <option value="month">This month</option>
                                <option value="year">This year</option>
                            </select>
                        </div>

                        <!-- Media Filter (only for posts) -->
                        @if($activeTab === 'posts')
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Content Type</label>
                                <select
                                    wire:model.live="mediaFilter"
                                    class="w-full bg-white dark:bg-zinc-700 border border-zinc-200 dark:border-zinc-600 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                    <option value="all">All content</option>
                                    <option value="text">Text only</option>
                                    <option value="image">Images</option>
                                    <option value="video">Videos</option>
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Loading State -->
            <div wire:loading class="flex items-center justify-center min-h-[400px] w-full">
                <div class="flex flex-col items-center space-y-4 text-zinc-600 dark:text-zinc-400">
                    <svg class="animate-spin h-8 w-8" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-lg font-medium">Searching...</span>
                </div>
            </div>

            <!-- Search Results -->
            <div wire:loading.remove class="space-y-6">
                <!-- Results Counter -->
                <div class="flex items-center justify-between mb-4">
                    <div class="text-sm text-zinc-600 dark:text-zinc-400">
                        @if($activeTab === 'posts')
                            {{ $posts->count() }} {{ Str::plural('post', $posts->count()) }} found
                        @else
                            {{ $users->count() }} {{ Str::plural('user', $users->count()) }} found
                        @endif
                    </div>
                    <div class="text-sm text-zinc-500 dark:text-zinc-500">
                        for "{{ $search }}"
                    </div>
                </div>

                @if($activeTab === 'posts')
                    <!-- Single Column Layout for Posts -->
                    <div id="posts-container" class="space-y-6">
                        @forelse($posts as $post)
                            <div class="overflow-hidden rounded-xl bg-white dark:bg-zinc-800 shadow-sm border border-zinc-200 dark:border-zinc-700 hover:shadow-md transition-shadow duration-200">
                                <!-- Post Header -->
                                <div class="flex items-center justify-between p-4">
                                    <div class="flex items-center space-x-3">
                                        <x-user-avatar :user="$post->user" size="md" />
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-zinc-900 dark:text-white">{{ Str::limit($post->user->username, 15, '...') ?? Str::limit($post->user->name, 15, '...') }}</h3>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $post->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    @if($post->user_id === auth()->id())
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300" type="button">
                                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                </svg>
                                            </button>

                                            <!-- Dropdown menu -->
                                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-zinc-800 rounded-md shadow-lg border border-zinc-200 dark:border-zinc-700 z-10">
                                                <div class="py-1">
                                        <flux:modal.trigger name="delete-post-{{ $post->id }}">
                                            <button
                                                wire:click="deletePost({{ $post->id }})"
                                                class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                                @click="open = false"
                                            >
                                                <svg class="h-4 w-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete Post
                                            </button>
                                        </flux:modal.trigger>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                @if($post->isImagePost() && $post->media_url)
                                    <!-- Post Image -->
                                    <div class="relative aspect-square bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center">
                                        <img src="{{ $post->media_url }}" alt="Post image" class="w-full h-full object-cover">

                                        <!-- Image info overlay -->
                                        <div class="absolute bottom-2 right-2">
                                            <a href="{{ route('posts.show', $post) }}" wire:navigate class="bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs hover:bg-opacity-70 transition-all">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                @elseif($post->isVideoPost() && $post->media_url)
                                    <!-- Post Video -->
                                    <div class="relative aspect-square bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center">
                                        <video
                                            loop
                                            playsinline
                                            preload="none"
                                            class="w-full h-full object-cover video-autoplay cursor-pointer"
                                            data-post-id="{{ $post->id }}"
                                            onloadstart="this.style.opacity='0.8'"
                                            oncanplay="this.style.opacity='1'"
                                        >
                                            <source src="{{ $post->media_url }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>

                                        <!-- Video info overlay -->
                                        <div class="absolute bottom-2 right-2">
                                            <a href="{{ route('posts.show', $post) }}" wire:navigate class="bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs hover:bg-opacity-70 transition-all">
                                                View
                                            </a>
                                        </div>
                                    </div>
                                @elseif($post->isTextPost() && $post->content)
                                    <!-- Post Content -->
                                    <a href="{{ route('posts.show', $post) }}" wire:navigate class="block">
                                        <div class="px-4 pb-4">
                                            <p class="text-zinc-900 dark:text-white leading-relaxed">
                                                {{ $post->content }}
                                            </p>
                                        </div>
                                    </a>
                                @endif

                                <!-- Post Actions -->
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-4">
                                            <button
                                                wire:click="toggleLike({{ $post->id }})"
                                                class="transition-colors {{ $this->isLiked($post->id) ? 'text-red-500 dark:text-red-400' : 'text-zinc-600 hover:text-red-500 dark:text-zinc-400 dark:hover:text-red-400' }}"
                                                type="button"
                                            >
                                                <svg class="h-6 w-6 {{ $this->isLiked($post->id) ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </button>
                                            <button
                                                wire:click="toggleComments({{ $post->id }})"
                                                class="text-zinc-600 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors"
                                                type="button"
                                            >
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                            </button>
                                            <button
                                                class="text-zinc-600 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors"
                                                type="button"
                                                onclick="copyShareLink({{ $post->id }})"
                                                data-post-id="{{ $post->id }}"
                                                title="Share post"
                                            >
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <button
                                            wire:click="toggleSave({{ $post->id }})"
                                            class="text-zinc-600 hover:text-blue-500 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors {{ $this->isSaved($post->id) ? 'text-blue-500 dark:text-blue-400' : '' }}"
                                            type="button"
                                        >
                                            <svg class="h-6 w-6 {{ $this->isSaved($post->id) ? 'fill-current text-blue-500 dark:text-blue-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Likes -->
                                    <p class="text-sm font-semibold text-zinc-900 dark:text-white mb-1">{{ number_format($post->likes_count) }} {{ Str::plural('like', $post->likes_count) }}</p>

                                    <!-- Caption -->
                                    @if($post->content)
                                        <div class="text-sm text-zinc-900 dark:text-white">
                                            <span class="font-semibold">{{ $post->user->username ?? $post->user->name }}</span>
                                            <span class="ml-2">{{ $post->content }}</span>
                                        </div>
                                    @endif

                                    <!-- Comments Toggle -->
                                    @if($post->comments_count > 0)
                                        <button
                                            wire:click="toggleComments({{ $post->id }})"
                                            class="mt-2 text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300"
                                            type="button"
                                        >
                                            @if(in_array($post->id, $showComments))
                                                Hide comments
                                            @else
                                                View all {{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}
                                            @endif
                                        </button>
                                    @endif

                                    <!-- Comments Section -->
                                    @if(in_array($post->id, $showComments))
                                        <div class="mt-3 space-y-3">
                                            @if(isset($comments[$post->id]))
                                                @foreach($comments[$post->id] as $comment)
                                                    <div class="flex items-start space-x-3">
                                                        <x-user-avatar :user="$comment->user" size="xs" />
                                                        <div class="flex-1">
                                                            <div class="text-sm">
                                                                <span class="font-semibold text-zinc-900 dark:text-white">{{ $comment->user->username ?? $comment->user->name }}</span>
                                                                <span class="text-zinc-900 dark:text-white ml-2">{{ $comment->content }}</span>
                                                            </div>
                                                            <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                                                {{ $comment->created_at->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Add Comment -->
                                    <div class="mt-3 flex items-center space-x-3">
                                        <x-user-avatar :user="auth()->user()" size="sm" />
                                        <div class="flex-1">
                                            <input
                                                type="text"
                                                wire:model="newComments.{{ $post->id }}"
                                                wire:keydown.enter="addComment({{ $post->id }})"
                                                placeholder="Add a comment..."
                                                class="w-full bg-transparent text-sm text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none"
                                            >
                                        </div>
                                        <button
                                            wire:click="addComment({{ $post->id }})"
                                            class="text-sm font-semibold text-blue-500 hover:text-blue-600"
                                            type="button"
                                        >
                                            Post
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex items-center justify-center min-h-[400px] w-full">
                                <div class="text-center">
                                    <div class="mx-auto w-24 h-24 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-6">
                                        <svg class="w-12 h-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.709M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xl font-semibold text-zinc-900 dark:text-white mb-3">No posts found</h3>
                                    <p class="text-zinc-600 dark:text-zinc-400 max-w-md mx-auto">Try adjusting your search terms or filters to find what you're looking for.</p>
                                </div>
                            </div>
                        @endforelse

                        <!-- Loading indicator -->
                        @if($loading)
                            <div class="flex justify-center py-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            </div>
                        @endif

                        <!-- Load more button -->
                        @if($hasMorePosts && !$loading && $posts->count() > 0)
                            <div class="flex justify-center py-8">
                                <flux:button
                                    wire:click="loadMore"
                                    variant="primary"
                                    class="px-6 py-2"
                                >
                                    Load More Posts
                                </flux:button>
                            </div>
                        @elseif(!$hasMorePosts && $posts->count() > 0)
                            <div class="text-center py-8">
                                <p class="text-zinc-500 dark:text-zinc-400">No more posts to load</p>
                            </div>
                        @endif
                @else
                    <div id="users-container" class="space-y-6">
                        @forelse($users as $user)
                        <div class="overflow-hidden rounded-xl bg-white dark:bg-zinc-800 shadow-sm border border-zinc-200 dark:border-zinc-700 hover:shadow-md transition-shadow duration-200">
                            <div class="p-6">
                                <div class="flex items-center space-x-4">
                                    <x-user-avatar :user="$user" size="xl" />
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('profile.show', $user->username) }}" wire:navigate class="text-lg font-semibold text-zinc-900 dark:text-white truncate hover:underline">
                                            {{ $user->name }}
                                        </a>
                                        <a href="{{ route('profile.show', $user->username) }}" wire:navigate class="text-sm text-zinc-500 dark:text-zinc-400 truncate hover:text-zinc-700 dark:hover:text-zinc-300">
                                            {{ '@' . $user->username }}
                                        </a>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400 truncate">
                                            {{ $user->email }}
                                        </p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                            Joined {{ $user->created_at->format('M Y') }}
                                        </p>
                                    </div>
                                    <div class="flex flex-col items-end space-y-2">
                                        <div class="text-sm text-zinc-500 dark:text-zinc-400">
                                            {{ $user->posts()->count() }} posts
                                        </div>
                                        @auth
                                            @if(auth()->user()->id !== $user->id)
                                                <button
                                                    wire:click="toggleFollow({{ $user->id }})"
                                                    class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors duration-200 {{ $this->isFollowing($user->id) ? 'bg-zinc-200 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-200 hover:bg-zinc-300 dark:hover:bg-zinc-600' : 'bg-blue-600 text-white hover:bg-blue-700' }}"
                                                >
                                                    {{ $this->isFollowing($user->id) ? 'Following' : 'Follow' }}
                                                </button>
                                            @endif
                                        @else
                                            <a
                                                href="{{ route('login') }}"
                                                class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200"
                                            >
                                                Follow
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center justify-center min-h-[400px] w-full">
                            <div class="text-center">
                                <div class="mx-auto w-24 h-24 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-6">
                                    <svg class="w-12 h-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-zinc-900 dark:text-white mb-3">No users found</h3>
                                <p class="text-zinc-600 dark:text-zinc-400 max-w-md mx-auto">Try adjusting your search terms or filters to find what you're looking for.</p>
                            </div>
                        </div>
                    @endforelse

                        <!-- Loading indicator -->
                        @if($loading)
                            <div class="flex justify-center py-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            </div>
                        @endif

                        <!-- Load more button -->
                        @if($hasMoreUsers && !$loading && $users->count() > 0)
                            <div class="flex justify-center py-8">
                                <flux:button
                                    variant="primary"
                                    wire:click="loadMore"
                                    class="px-6 py-2"
                                >
                                    Load More Users
                                </flux:button>
                            </div>
                        @elseif(!$hasMoreUsers && $users->count() > 0)
                            <div class="text-center py-8">
                                <p class="text-zinc-500 dark:text-zinc-400">No more users to load</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="mx-auto w-32 h-32 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-16 h-16 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2">Start searching</h2>
                <p class="text-zinc-600 dark:text-zinc-400 mb-8 max-w-md mx-auto">
                    Search for posts, users, or anything else on Thread Loop. Use the search bar above to get started.
                </p>

                <!-- Search Suggestions -->
                <div class="max-w-2xl mx-auto">
                    <h3 class="text-lg font-medium text-zinc-900 dark:text-white mb-4">Popular searches</h3>
                    <div class="flex flex-wrap gap-2 justify-center">
                        <button
                            wire:click="selectSuggestion('technology')"
                            class="px-4 py-2 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded-full text-sm hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                        >
                            #technology
                        </button>
                        <button
                            wire:click="selectSuggestion('design')"
                            class="px-4 py-2 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded-full text-sm hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                        >
                            #design
                        </button>
                        <button
                            wire:click="selectSuggestion('programming')"
                            class="px-4 py-2 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded-full text-sm hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                        >
                            #programming
                        </button>
                        <button
                            wire:click="selectSuggestion('lifestyle')"
                            class="px-4 py-2 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 rounded-full text-sm hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                        >
                            #lifestyle
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Post Confirmation Modal -->
    @if($postToDelete)
        <flux:modal name="delete-post-{{ $postToDelete }}" class="min-w-[22rem]" :closable="false" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete post?</flux:heading>
                    <flux:text class="mt-2">
                        <p>You're about to delete this post.</p>
                        <p>This action cannot be reversed.</p>
                    </flux:text>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button wire:click="cancelDeletePost" variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="confirmDeletePost" variant="danger">
                        Delete post
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif

    <!-- Enhanced Styles for Search Page -->
    <style>
        /* Enhanced video autoplay styles */
        .video-autoplay {
            transition: opacity 0.3s ease-in-out;
        }

        .video-autoplay.loading {
            opacity: 0.8;
        }

        .video-autoplay.playing {
            opacity: 1;
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Loading indicator for infinite scroll */
        .infinite-scroll-loading {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .infinite-scroll-loading .spinner {
            width: 2rem;
            height: 2rem;
            border: 2px solid #e5e7eb;
            border-top: 2px solid #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Search results optimization */
        #posts-container {
            max-width: 100%;
        }

        /* Responsive adjustments for better mobile experience */
        @media (max-width: 640px) {
            .min-h-screen {
                padding: 1rem;
            }

            .mx-auto.max-w-7xl {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            /* Improve card spacing on mobile */
            .space-y-6 > * + * {
                margin-top: 1rem;
            }

            /* Better button spacing */
            .flex.items-center.space-x-4 > * + * {
                margin-left: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            /* Even tighter spacing for very small screens */
            .p-4 {
                padding: 0.75rem;
            }

            .px-4 {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .py-4 {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }
        }
    </style>

    <script>
        function copyShareLink(postId) {
            const url = `{{ url('/share') }}/${postId}`;
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;

            // Get post ID from data attribute as fallback
            const actualPostId = postId || button.dataset.postId;

            // Try modern clipboard API first (requires HTTPS)
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(function() {
                    showSuccessMessage(button, originalHTML);
                    // Fire server hook when copied
                    fetch(`{{ url('/share') }}/${actualPostId}/copied`, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
                }).catch(function(err) {
                    console.error('Clipboard API failed:', err);
                    fallbackCopyTextToClipboard(url, button, originalHTML, actualPostId);
                });
            } else {
                // Fallback for HTTP or older browsers
                fallbackCopyTextToClipboard(url, button, originalHTML, actualPostId);
            }
        }

        function fallbackCopyTextToClipboard(text, button, originalHTML, postId) {
            // Create a temporary textarea element
            const textArea = document.createElement("textarea");
            textArea.value = text;

            // Avoid scrolling to bottom
            textArea.style.top = "0";
            textArea.style.left = "0";
            textArea.style.position = "fixed";
            textArea.style.opacity = "0";

            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showSuccessMessage(button, originalHTML);
                    // Fire server hook when copied
                    fetch(`{{ url('/share') }}/${postId}/copied`, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
                } else {
                    showErrorMessage(button, originalHTML, text, postId);
                }
            } catch (err) {
                console.error('Fallback copy failed:', err);
                showErrorMessage(button, originalHTML, text, postId);
            }

            document.body.removeChild(textArea);
        }

        function showSuccessMessage(button, originalHTML) {
            button.innerHTML = `
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            `;
            button.classList.add('text-green-600', 'dark:text-green-400');
            button.title = 'Link copied!';

            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('text-green-600', 'dark:text-green-400');
                button.title = 'Share post';
            }, 2000);
        }

        function showErrorMessage(button, originalHTML, url, postId) {
            button.innerHTML = `
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            `;
            button.classList.add('text-red-600', 'dark:text-red-400');
            button.title = 'Click to copy manually';

            // Add click handler to copy manually
            button.onclick = function(e) {
                e.preventDefault();
                prompt('Copy this link:', url);
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.classList.remove('text-red-600', 'dark:text-red-400');
                    button.title = 'Share post';
                    button.onclick = function() {
                        copyShareLink(postId);
                    };
                }, 3000);
            };

            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('text-red-600', 'dark:text-red-400');
                button.title = 'Share post';
            }, 5000);
        }
    </script>
</div>
