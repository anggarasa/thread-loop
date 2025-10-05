<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                <div class="space-y-6">
                    @foreach($posts as $post)
                        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm">
                            <!-- Post Header -->
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <span class="text-white text-sm font-semibold">{{ $post->user->initials() }}</span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                                            {{ $post->user->name }}
                                        </h3>
                                        <span class="text-sm text-zinc-500 dark:text-zinc-400">
                                            @{{ $post->user->username }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">
                                        {{ $post->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            <!-- Post Content -->
                            <div class="mb-4">
                                <p class="text-zinc-900 dark:text-zinc-100 whitespace-pre-wrap">{{ $post->content }}</p>
                            </div>

                            <!-- Post Media -->
                            @if($post->media_url)
                                <div class="mb-4">
                                    @if($post->media_type === 'image')
                                        <img src="{{ $post->media_url }}"
                                             alt="Post image"
                                             class="max-w-full h-auto rounded-lg">
                                    @elseif($post->media_type === 'video')
                                        <video controls class="max-w-full h-auto rounded-lg">
                                            <source src="{{ $post->media_url }}" type="video/mp4">
                                            {{ __('Your browser does not support the video tag.') }}
                                        </video>
                                    @endif
                                </div>
                            @endif

                            <!-- Post Stats -->
                            <div class="flex items-center justify-between text-sm text-zinc-500 dark:text-zinc-400 border-t border-zinc-200 dark:border-zinc-700 pt-4">
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span>{{ $post->likes_count }}</span>
                                    </span>
                                    <span class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <span>{{ $post->comments_count }}</span>
                                    </span>
                                </div>
                                <div class="text-xs">
                                    <a href="{{ route('login') }}"
                                       class="text-blue-600 dark:text-blue-400 hover:underline"
                                       wire:navigate>
                                        {{ __('Sign in to interact') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Load More Button -->
                @if($hasMorePosts)
                    <div class="text-center mt-8">
                        <button wire:click="loadMore"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-6 py-3 border border-zinc-300 dark:border-zinc-600 text-base font-medium rounded-md text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="loadMore">{{ __('Load More Posts') }}</span>
                            <span wire:loading wire:target="loadMore">{{ __('Loading...') }}</span>
                        </button>
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($users as $user)
                        <div class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 dark:border-zinc-700 p-6 shadow-sm">
                            <div class="text-center">
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mx-auto mb-4">
                                    <span class="text-white text-lg font-semibold">{{ $user->initials() }}</span>
                                </div>
                                <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-100 mb-1">
                                    {{ $user->name }}
                                </h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
                                    @{{ $user->username }}
                                </p>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400 mb-4">
                                    {{ __('Joined') }} {{ $user->created_at->diffForHumans() }}
                                </div>
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors"
                                   wire:navigate>
                                    {{ __('Follow') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Load More Button -->
                @if($hasMoreUsers)
                    <div class="text-center mt-8">
                        <button wire:click="loadMore"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center px-6 py-3 border border-zinc-300 dark:border-zinc-600 text-base font-medium rounded-md text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="loadMore">{{ __('Load More People') }}</span>
                            <span wire:loading wire:target="loadMore">{{ __('Loading...') }}</span>
                        </button>
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
