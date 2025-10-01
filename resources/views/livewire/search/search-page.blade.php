<div class="min-h-screen">
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
                            {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} found
                        @else
                            {{ $users->total() }} {{ Str::plural('user', $users->total()) }} found
                        @endif
                    </div>
                    <div class="text-sm text-zinc-500 dark:text-zinc-500">
                        for "{{ $search }}"
                    </div>
                </div>

                @if($activeTab === 'posts')
                    <!-- Grid Layout for Posts -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @forelse($posts as $post)
                            <div class="overflow-hidden rounded-xl bg-white dark:bg-zinc-800 shadow-sm border border-zinc-200 dark:border-zinc-700">
                                <!-- Post Header -->
                                <div class="flex items-center justify-between p-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-pink-500 via-red-500 to-yellow-500 p-0.5">
                                            <div class="h-full w-full rounded-full bg-white dark:bg-zinc-800 p-0.5">
                                                <div class="h-full w-full rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                                    <span class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">{{ $post->user->initials() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-zinc-900 dark:text-white truncate">{{ $post->user->username ?? $post->user->name }}</h3>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $post->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($post->isImagePost() && $post->media_url)
                                    <!-- Post Image -->
                                    <div class="aspect-square bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center">
                                        <img src="{{ $post->media_url }}" alt="Post image" class="w-full h-full object-cover">
                                    </div>
                                @elseif($post->isVideoPost() && $post->media_url)
                                    <!-- Post Video -->
                                    <div class="aspect-square bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center">
                                        <video controls class="w-full h-full object-cover">
                                            <source src="{{ $post->media_url }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                @elseif($post->isTextPost() && $post->content)
                                    <!-- Post Content -->
                                    <div class="px-4 pb-4">
                                        <p class="text-zinc-900 dark:text-white leading-relaxed line-clamp-3">
                                            {{ $post->content }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Post Actions -->
                                <div class="p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-4">
                                            <button class="text-zinc-600 hover:text-red-500 dark:text-zinc-400 dark:hover:text-red-400 transition-colors">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            </button>
                                            <button class="text-zinc-600 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                            </button>
                                            <button class="text-zinc-600 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <button class="text-zinc-600 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Post Stats -->
                                    <div class="flex items-center space-x-4 text-sm text-zinc-500 dark:text-zinc-400">
                                        <span>{{ $post->likes_count }} likes</span>
                                        <span>{{ $post->comments_count }} comments</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                        <div class="col-span-full flex items-center justify-center min-h-[400px] w-full">
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

                    @if($posts->hasPages())
                        <div class="mt-8">
                            {{ $posts->links() }}
                        </div>
                    @endif
                @else
                    @forelse($users as $user)
                        <div class="overflow-hidden rounded-xl bg-white dark:bg-zinc-800 shadow-sm border border-zinc-200 dark:border-zinc-700 hover:shadow-md transition-shadow duration-200">
                            <div class="p-6">
                                <div class="flex items-center space-x-4">
                                    <div class="h-16 w-16 rounded-full bg-gradient-to-tr from-pink-500 via-red-500 to-yellow-500 p-0.5">
                                        <div class="h-full w-full rounded-full bg-white dark:bg-zinc-800 p-0.5">
                                            <div class="h-full w-full rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                                <span class="text-xl font-semibold text-zinc-600 dark:text-zinc-300">{{ $user->initials() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white truncate">
                                            {{ $user->name }}
                                        </h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400 truncate">
                                            {{ '@' . $user->username }}
                                        </p>
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
                                        <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                            Follow
                                        </button>
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

                    @if($users->hasPages())
                        <div class="mt-8">
                            {{ $users->links() }}
                        </div>
                    @endif
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
</div>
