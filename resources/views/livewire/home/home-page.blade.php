<!-- Main Grid Container -->
<div class="mx-auto max-w-7xl px-4 py-6 lg:px-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Main Feed -->
            <div class="space-y-6" id="posts-container">
                @forelse($posts as $post)
                    <div class="overflow-hidden rounded-xl bg-white dark:bg-zinc-800 shadow-sm border border-zinc-200 dark:border-zinc-700 hover:shadow-md transition-shadow">
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
                                <div>
                                    <a href="{{ route('profile.show', $post->user->username) }}" wire:navigate class="font-semibold text-zinc-900 dark:text-white hover:underline">{{ $post->user->username ?? $post->user->name }}</a>
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @livewire('follow-button', ['user' => $post->user])
                                <button class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300" type="button" onclick="event.preventDefault();">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        @if($post->isImagePost() && $post->media_url)
                            <!-- Post Image -->
                            <a href="{{ route('posts.show', $post) }}" wire:navigate class="block">
                                <div class="aspect-square bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center">
                                    <img src="{{ $post->media_url }}" alt="Post image" class="w-full h-full object-cover">
                                </div>
                            </a>
                        @elseif($post->isVideoPost() && $post->media_url)
                            <!-- Post Video -->
                            <a href="{{ route('posts.show', $post) }}" wire:navigate class="block">
                                <div class="aspect-square bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center">
                                    <video
                                        controls
                                        muted
                                        loop
                                        playsinline
                                        preload="metadata"
                                        class="w-full h-full object-cover video-autoplay"
                                        data-post-id="{{ $post->id }}"
                                        onloadstart="this.style.opacity='0.8'"
                                        oncanplay="this.style.opacity='1'"
                                    >
                                        <source src="{{ $post->media_url }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            </a>
                        @elseif($post->isTextPost() && $post->content)
                            <!-- Text Post Content -->
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
                                        class="text-zinc-600 hover:text-red-500 dark:text-zinc-400 dark:hover:text-red-400 transition-colors {{ $this->isLiked($post->id) ? 'text-red-500 dark:text-red-400' : '' }}"
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
                                    <button class="text-zinc-600 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors" type="button" onclick="event.preventDefault();">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <button class="text-zinc-600 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors" type="button" onclick="event.preventDefault();">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                <div class="h-6 w-6 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center flex-shrink-0">
                                                    <span class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">{{ substr($comment->user->name, 0, 1) }}</span>
                                                </div>
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
                                <div class="h-8 w-8 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                    <span class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
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
                    <!-- No Posts State -->
                    <div class="text-center py-12">
                        <div class="mx-auto h-24 w-24 rounded-full bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4">
                            <svg class="h-12 w-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">No posts yet</h3>
                        <p class="text-zinc-500 dark:text-zinc-400 mb-6">Be the first to share something with the community!</p>
                        <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create your first post
                        </a>
                    </div>
                @endforelse

                <!-- Loading indicator -->
                @if($loading)
                    <div class="flex justify-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
                @endif

                <!-- Load more button -->
                @if($hasMorePosts && !$loading)
                    <div class="flex justify-center py-8">
                        <button
                            wire:click="loadMore"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Load More Posts
                        </button>
                    </div>
                @elseif(!$hasMorePosts)
                    <div class="text-center py-8">
                        <p class="text-zinc-500 dark:text-zinc-400">No more posts to load</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Sidebar - Recommendations -->
        <div class="lg:col-span-1">
            <div class="top-6">
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
                    <!-- User Profile Summary -->
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 p-0.5">
                            <div class="h-full w-full rounded-full bg-white dark:bg-zinc-800 p-0.5">
                                <div class="h-full w-full rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                    <span class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-zinc-900 dark:text-white">{{ auth()->user()->name }}</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email }}</p>
                        </div>
                    </div>

                    <!-- Suggestions for You -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="font-semibold text-zinc-900 dark:text-white">Suggestions for You</h4>
                            <button class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200">See All</button>
                        </div>

                        <div class="space-y-3">
                            @forelse($suggestedUsers as $user)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-pink-500 via-red-500 to-yellow-500 p-0.5">
                                        <div class="h-full w-full rounded-full bg-white dark:bg-zinc-800 p-0.5">
                                            <div class="h-full w-full rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                                <span class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">{{ $user->initials() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $user->username ?? $user->name }}</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Suggested for you</p>
                                    </div>
                                </div>
                                <button class="text-sm font-semibold text-blue-500 hover:text-blue-600">Follow</button>
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">No suggestions available</p>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Footer Links -->
                    <div class="text-xs text-zinc-500 dark:text-zinc-400 space-y-2">
                        <div class="flex flex-wrap gap-x-4 gap-y-1">
                            <a href="#" class="hover:underline">About</a>
                            <a href="#" class="hover:underline">Help</a>
                            <a href="#" class="hover:underline">Press</a>
                            <a href="#" class="hover:underline">API</a>
                            <a href="#" class="hover:underline">Jobs</a>
                            <a href="#" class="hover:underline">Privacy</a>
                            <a href="#" class="hover:underline">Terms</a>
                            <a href="#" class="hover:underline">Locations</a>
                            <a href="#" class="hover:underline">Language</a>
                        </div>
                        <p class="pt-2">© 2024 ThreadLoop</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
<style>
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    /* Video autoplay styles */
    .video-autoplay {
        transition: opacity 0.3s ease-in-out;
    }

    .video-autoplay.loading {
        opacity: 0.8;
    }

    .video-autoplay.playing {
        opacity: 1;
    }
</style>

<!-- Infinite Scroll Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    let isLoading = false;
    const postsContainer = document.getElementById('posts-container');

    // Function to check if user is near bottom of page
    function isNearBottom() {
        return (window.innerHeight + window.scrollY) >= (document.body.offsetHeight - 1000);
    }

    // Function to load more posts
    function loadMorePosts() {
        if (isLoading) return;

        // Check if Livewire component exists and has more posts
        if (typeof Livewire !== 'undefined' && @this.hasMorePosts && !@this.loading) {
            isLoading = true;
            @this.call('loadMore').then(() => {
                isLoading = false;
            });
        }
    }

    // Throttled scroll handler
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }

        scrollTimeout = setTimeout(function() {
            if (isNearBottom()) {
                loadMorePosts();
            }
        }, 100);
    });

    // Also listen for Livewire updates to reset loading state
    document.addEventListener('livewire:updated', function() {
        isLoading = false;
    });
});
</script>
</div>
