<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" data-page="search">
    <!-- Search Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-zinc-900 dark:text-zinc-100 mb-4">
            {{ __('Search ThreadLoop') }}
        </h1>
        <p class="text-lg text-zinc-600 dark:text-zinc-400">
            {{ __('Find posts and people you\'re interested in') }}
        </p>
    </div>

    <!-- Search Bar -->
    <div class="relative mb-8">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-zinc-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search"
                   type="text"
                   class="block w-full pl-10 pr-12 py-3 border border-zinc-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 placeholder-zinc-500 dark:placeholder-zinc-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-400 dark:focus:border-blue-400"
                   placeholder="{{ __('Search posts, people...') }}">
            @if($search)
                <button wire:click="clearSearch"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-zinc-400 dark:text-zinc-500 hover:text-zinc-600 dark:hover:text-zinc-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            @endif
        </div>

        <!-- Search Suggestions -->
        @if($showSuggestions && $suggestions->count() > 0)
            <div class="absolute z-10 w-full mt-1 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-lg">
                @foreach($suggestions as $suggestion)
                    <button wire:click="selectSuggestion('{{ $suggestion['value'] }}')"
                            class="w-full px-4 py-3 text-left hover:bg-zinc-50 dark:hover:bg-zinc-700 flex items-center space-x-3">
                        <svg class="h-5 w-5 text-zinc-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($suggestion['icon'] === 'user')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            @endif
                        </svg>
                        <span class="text-zinc-900 dark:text-zinc-100">{{ $suggestion['text'] }}</span>
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    @if($search)
        <!-- Search Tabs -->
        <div class="mb-6">
            <div class="border-b border-zinc-200 dark:border-zinc-700">
                <nav class="-mb-px flex space-x-8">
                    <button wire:click="$set('activeTab', 'posts')"
                            class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'posts' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                        {{ __('Posts') }}
                    </button>
                    <button wire:click="$set('activeTab', 'users')"
                            class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'users' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300 hover:border-zinc-300 dark:hover:border-zinc-600' }}">
                        {{ __('People') }}
                    </button>
                </nav>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <button wire:click="toggleFilters"
                        class="inline-flex items-center px-3 py-2 border border-zinc-300 dark:border-zinc-600 text-sm font-medium rounded-md text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    {{ __('Filters') }}
                </button>

                <div class="flex items-center space-x-4">
                    <select wire:model.live="sortBy"
                            class="text-sm border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100">
                        <option value="recent">{{ __('Most Recent') }}</option>
                        <option value="popular">{{ __('Most Popular') }}</option>
                        @if($activeTab === 'users')
                            <option value="name">{{ __('Name A-Z') }}</option>
                            <option value="username">{{ __('Username A-Z') }}</option>
                        @endif
                    </select>
                </div>
            </div>

            @if($showFilters)
                <div class="mt-4 p-4 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('Date') }}</label>
                            <select wire:model.live="dateFilter"
                                    class="w-full text-sm border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100">
                                <option value="all">{{ __('All Time') }}</option>
                                <option value="today">{{ __('Today') }}</option>
                                <option value="week">{{ __('This Week') }}</option>
                                <option value="month">{{ __('This Month') }}</option>
                                <option value="year">{{ __('This Year') }}</option>
                            </select>
                        </div>
                        @if($activeTab === 'posts')
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">{{ __('Media Type') }}</label>
                                <select wire:model.live="mediaFilter"
                                        class="w-full text-sm border border-zinc-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100">
                                    <option value="all">{{ __('All Types') }}</option>
                                    <option value="text">{{ __('Text Only') }}</option>
                                    <option value="image">{{ __('Images') }}</option>
                                    <option value="video">{{ __('Videos') }}</option>
                                </select>
                            </div>
                        @endif
                    </div>
                    <div class="mt-4">
                        <button wire:click="clearFilters"
                                class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                            {{ __('Clear Filters') }}
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Search Results -->
        @if($activeTab === 'posts')
            @if($posts->count() > 0)
                <!-- Grid Layout for Posts -->
                <div id="posts-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <a href="{{ route('login') }}" wire:navigate class="block overflow-hidden rounded-xl bg-white dark:bg-zinc-800 shadow-sm border border-zinc-200 dark:border-zinc-700 hover:shadow-md transition-shadow duration-200">
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
                                        <h3 class="font-semibold text-zinc-900 dark:text-white">{{ Str::limit($post->user->username, 15, '...') ?? Str::limit($post->user->name, 15, '...') }}</h3>
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
                                    <span>{{ $post->likes_count }}</span>
                                    <span>{{ $post->comments_count }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Loading indicator -->
                @if($loading)
                    <div class="col-span-full flex justify-center py-8"></div>
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
                @endif

                <!-- Load More Button -->
                @if($hasMorePosts && !$loading && $posts->count() > 0)
                    <div class="col-span-full flex justify-center py-8">
                        <flux:button
                            wire:click="loadMore"
                            variant="primary"
                            class="px-6 py-2">
                            {{ __('Load More Posts') }}
                        </flux:button>
                    </div>
                @elseif(!$hasMorePosts && $posts->count() > 0)
                    <div class="col-span-full text-center py-8">
                        <p class="text-zinc-500 dark:text-zinc-400">{{ __('No more posts to load') }}</p>
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-zinc-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('No posts found') }}</h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Try adjusting your search terms or filters.') }}</p>
                </div>
            @endif
        @else
            @if($users->count() > 0)
                <div id="users-container" class="space-y-6">
                    @foreach($users as $user)
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
                                        <a href="{{ route('login') }}" wire:navigate class="text-lg font-semibold text-zinc-900 dark:text-white truncate hover:underline">
                                            {{ $user->name }}
                                        </a>
                                        <a href="{{ route('login') }}" wire:navigate class="text-sm text-zinc-500 dark:text-zinc-400 truncate hover:text-zinc-700 dark:hover:text-zinc-300">
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
                                        <a href="{{ route('login') }}"
                                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors"
                                           wire:navigate>
                                            {{ __('Follow') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Loading indicator -->
                @if($loading)
                    <div class="flex justify-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
                @endif

                <!-- Load More Button -->
                @if($hasMoreUsers && !$loading && $users->count() > 0)
                    <div class="flex justify-center py-8">
                        <flux:button
                            wire:click="loadMore"
                            variant="primary"
                            class="px-6 py-2">
                            {{ __('Load More Users') }}
                        </flux:button>
                    </div>
                @elseif(!$hasMoreUsers && $users->count() > 0)
                    <div class="text-center py-8">
                        <p class="text-zinc-500 dark:text-zinc-400">{{ __('No more users to load') }}</p>
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-zinc-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('No people found') }}</h3>
                    <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Try adjusting your search terms or filters.') }}</p>
                </div>
            @endif
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-zinc-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('Start searching') }}</h3>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Enter a search term to find posts and people.') }}</p>
        </div>
    @endif
</div>
