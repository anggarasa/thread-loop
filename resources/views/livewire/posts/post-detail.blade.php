<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
    <!-- Header with Back Button -->
    <div class="sticky top-0 z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-700">
        <div class="max-w-4xl mx-auto px-4 py-3">
            <div class="flex items-center space-x-4">
                <a href="{{ route('homePage') }}" wire:navigate
                    class="p-2 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-full transition-colors"
                >
                    <svg class="w-6 h-6 text-zinc-600 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-lg font-semibold text-zinc-900 dark:text-white">Post</h1>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-6">
        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
            <!-- Top Section: Post Image/Video Area -->
            <div class="relative">
                @if($post->isImagePost() && $post->media_url)
                    <div class="aspect-video bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center">
                        <img
                            src="{{ $post->media_url }}"
                            alt="Post image"
                            class="w-full h-full object-cover"
                            onload="adjustImageFit(this)"
                        >
                    </div>
                @elseif($post->isVideoPost() && $post->media_url)
                    <div class="aspect-video bg-zinc-100 dark:bg-zinc-700 flex items-center justify-center">
                        <video
                            controls
                            muted
                            loop
                            playsinline
                            preload="metadata"
                            class="w-full h-full video-autoplay"
                            data-post-id="{{ $post->id }}"
                        >
                            <source src="{{ $post->media_url }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @else
                    <!-- Text-only post placeholder -->
                    <div class="aspect-video bg-gradient-to-br from-blue-50 to-purple-50 dark:from-zinc-800 dark:to-zinc-700 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-6xl mb-4">📝</div>
                            <p class="text-zinc-600 dark:text-zinc-300 text-lg">Text Post</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Bottom Section: Content and Comments Area -->
            <div class="p-6 space-y-6">
                <!-- Post Header -->
                <div class="flex items-center space-x-3 mb-4">
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

                <!-- Post Content -->
                @if($post->content)
                    <div class="text-zinc-900 dark:text-white mb-4">
                        <p>{{ $post->content }}</p>
                    </div>
                @endif

                <!-- Post Actions -->
                <div class="flex items-center space-x-6 mb-4">
                    <button
                        wire:click="toggleLike"
                        class="flex items-center space-x-2 text-zinc-600 hover:text-red-500 dark:text-zinc-400 dark:hover:text-red-400 transition-colors {{ $isLiked ? 'text-red-500 dark:text-red-400' : '' }}"
                    >
                        <svg class="h-6 w-6 {{ $isLiked ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="text-sm font-semibold">{{ number_format($likesCount) }}</span>
                    </button>
                    <button
                        wire:click="toggleComments"
                        class="flex items-center space-x-2 text-zinc-600 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="text-sm font-semibold">{{ $post->comments_count }}</span>
                    </button>
                </div>

                <!-- Comment Input Area -->
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-pink-500 via-red-500 to-yellow-500 p-0.5 flex-shrink-0">
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
                            placeholder="Input Comment"
                            class="w-full px-4 py-2 bg-zinc-100 dark:bg-zinc-700 text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 rounded-full border border-zinc-200 dark:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                    @if($newComment)
                        <button
                            wire:click="addComment"
                            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-full text-sm font-semibold transition-colors"
                        >
                            Send
                        </button>
                    @endif
                </div>

                <!-- Separator Line -->
                <div class="border-t border-zinc-200 dark:border-zinc-700"></div>

                <!-- User Comments Area -->
                <div class="space-y-4">
                    @if($showComments && $comments->count() > 0)
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
                                    <div class="bg-zinc-100 dark:bg-zinc-700 rounded-2xl px-4 py-3">
                                        <p class="text-sm font-semibold text-zinc-900 dark:text-white mb-1">{{ $comment->user->username ?? $comment->user->name }}</p>
                                        <p class="text-sm text-zinc-900 dark:text-white">{{ $comment->content }}</p>
                                    </div>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 ml-4">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @endforeach

                        {{ $comments->links() }}
                    @elseif($post->comments_count > 0)
                        <div class="text-center">
                            <button
                                wire:click="toggleComments"
                                class="text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300"
                            >
                                View all {{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}
                            </button>
                        </div>
                    @else
                        <div class="text-center text-zinc-500 dark:text-zinc-400">
                            <p class="text-sm">No comments yet. Be the first to comment!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function adjustImageFit(img) {
            // Wait for image to load completely
            if (img.complete && img.naturalWidth && img.naturalHeight) {
                const aspectRatio = img.naturalWidth / img.naturalHeight;

                // If landscape (wider than tall), use object-cover
                if (aspectRatio > 1.2) {
                    img.classList.remove('object-contain');
                    img.classList.add('object-cover');
                }
                // If portrait (taller than wide), use object-contain to prevent cropping
                else if (aspectRatio < 0.8) {
                    img.classList.remove('object-cover');
                    img.classList.add('object-contain');
                }
                // For square images (1:1), use object-contain to show full image
                else {
                    img.classList.remove('object-cover');
                    img.classList.add('object-contain');
                }
            }
        }

        // Handle images that are already loaded
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('img[onload="adjustImageFit(this)"]');
            images.forEach(img => {
                if (img.complete) {
                    adjustImageFit(img);
                }
            });
        });
    </script>

</div>
