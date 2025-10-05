<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-zinc-900 dark:text-zinc-100 mb-4">
            {{ __('Welcome to ThreadLoop') }}
        </h1>
        <p class="text-lg text-zinc-600 dark:text-zinc-400 mb-8">
            {{ __('Discover amazing posts and connect with people around the world') }}
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}"
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors"
               wire:navigate>
                {{ __('Join ThreadLoop') }}
            </a>
            <a href="{{ route('login') }}"
               class="inline-flex items-center px-6 py-3 border border-zinc-300 dark:border-zinc-600 text-base font-medium rounded-md text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-800 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors"
               wire:navigate>
                {{ __('Sign In') }}
            </a>
        </div>
    </div>

    <!-- Posts Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100">
                {{ __('Recent Posts') }}
            </h2>
        </div>

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
                <h3 class="mt-2 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ __('No posts yet') }}</h3>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('Be the first to share something amazing!') }}</p>
                <div class="mt-6">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors"
                       wire:navigate>
                        {{ __('Get Started') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
