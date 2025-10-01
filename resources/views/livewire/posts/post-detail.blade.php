<div class="min-h-screen">
    <!-- Header with Back Button -->
    <div class="sticky top-0 z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-700">
        <div class="max-w-4xl mx-auto px-4 py-3">
            <div class="flex items-center space-x-4">
                <button
                    wire:click="goBack"
                    class="p-2 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-full transition-colors"
                >
                    <svg class="w-6 h-6 text-zinc-600 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <h1 class="text-lg font-semibold text-zinc-900 dark:text-white">Post</h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Post Content -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
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
                                <h3 class="font-semibold text-zinc-900 dark:text-white">{{ $post->user->username ?? $post->user->name }}</h3>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <button class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 p-2 hover:bg-zinc-100 dark:hover:bg-zinc-700 rounded-full transition-colors">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Post Media -->
                    @if($post->isImagePost() && $post->media_url)
                        <div class="aspect-square bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center">
                            <img src="{{ $post->media_url }}" alt="Post image" class="w-full h-full object-cover">
                        </div>
                    @elseif($post->isVideoPost() && $post->media_url)
                        <div class="aspect-square bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500 flex items-center justify-center">
                            <video
                                controls
                                muted
                                loop
                                playsinline
                                preload="metadata"
                                class="w-full h-full object-cover video-autoplay"
                                data-post-id="{{ $post->id }}"
                            >
                                <source src="{{ $post->media_url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @endif

                    <!-- Post Actions -->
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-4">
                                <button
                                    wire:click="toggleLike"
                                    class="text-zinc-600 hover:text-red-500 dark:text-zinc-400 dark:hover:text-red-400 transition-colors {{ $isLiked ? 'text-red-500 dark:text-red-400' : '' }}"
                                >
                                    <svg class="h-6 w-6 {{ $isLiked ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                                <button
                                    wire:click="toggleComments"
                                    class="text-zinc-600 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors"
                                >
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

                        <!-- Likes Count -->
                        <p class="text-sm font-semibold text-zinc-900 dark:text-white mb-1">
                            {{ number_format($likesCount) }} {{ Str::plural('like', $likesCount) }}
                        </p>

                        <!-- Caption -->
                        @if($post->content)
                            <div class="text-sm text-zinc-900 dark:text-white mb-2">
                                <span class="font-semibold">{{ $post->user->username ?? $post->user->name }}</span>
                                <span class="ml-2">{{ $post->content }}</span>
                            </div>
                        @endif

                        <!-- Comments Toggle -->
                        @if($post->comments_count > 0)
                            <button
                                wire:click="toggleComments"
                                class="text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300 mb-3"
                            >
                                View all {{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}
                            </button>
                        @endif

                        <!-- Comments Section -->
                        @if($showComments)
                            <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                                @foreach($comments as $comment)
                                    <div class="flex space-x-3">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-pink-500 via-red-500 to-yellow-500 p-0.5 flex-shrink-0">
                                            <div class="h-full w-full rounded-full bg-white dark:bg-zinc-800 p-0.5">
                                                <div class="h-full w-full rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                                    <span class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">{{ $comment->user->initials() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="bg-zinc-100 dark:bg-zinc-700 rounded-2xl px-3 py-2">
                                                <p class="text-sm font-semibold text-zinc-900 dark:text-white">{{ $comment->user->username ?? $comment->user->name }}</p>
                                                <p class="text-sm text-zinc-900 dark:text-white">{{ $comment->content }}</p>
                                            </div>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach

                                {{ $comments->links() }}
                            </div>
                        @endif

                        <!-- Add Comment Form -->
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-pink-500 via-red-500 to-yellow-500 p-0.5">
                                <div class="h-full w-full rounded-full bg-white dark:bg-zinc-800 p-0.5">
                                    <div class="h-full w-full rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                        <span class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1">
                                <input
                                    type="text"
                                    wire:model="newComment"
                                    wire:keydown.enter="addComment"
                                    placeholder="Add a comment..."
                                    class="w-full bg-transparent text-sm text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none border-none"
                                >
                            </div>
                            @if($newComment)
                                <button
                                    wire:click="addComment"
                                    class="text-sm font-semibold text-blue-500 hover:text-blue-600 transition-colors"
                                >
                                    Post
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar - Suggested Posts -->
            <div class="lg:col-span-1">
                <div class="sticky top-20">
                    <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6">
                        <h3 class="font-semibold text-zinc-900 dark:text-white mb-4">More from {{ $post->user->username ?? $post->user->name }}</h3>

                        <div class="space-y-4">
                            @forelse($suggestedPosts as $suggestedPost)
                                <a href="{{ route('posts.show', $suggestedPost) }}" class="block group">
                                    <div class="bg-zinc-100 dark:bg-zinc-700 rounded-lg overflow-hidden aspect-square">
                                        @if($suggestedPost->isImagePost() && $suggestedPost->media_url)
                                            <img src="{{ $suggestedPost->media_url }}" alt="Suggested post" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @elseif($suggestedPost->isVideoPost() && $suggestedPost->media_url)
                                            <div class="relative w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-400 via-purple-500 to-pink-500">
                                                <video class="w-full h-full object-cover" muted>
                                                    <source src="{{ $suggestedPost->media_url }}" type="video/mp4">
                                                </video>
                                                <div class="absolute inset-0 bg-black/20 flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M8 5v10l7-5-7-5z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <p class="text-zinc-500 dark:text-zinc-400 text-sm">{{ Str::limit($suggestedPost->content, 50) }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-sm text-zinc-500 dark:text-zinc-400">No other posts available</p>
                                </div>
                            @endforelse
                        </div>
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

    /* Smooth animations */
    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
</div>
