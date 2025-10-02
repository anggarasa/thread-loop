<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
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

    <!-- Main Content - Vertical Layout -->
    <div class="max-w-4xl mx-auto px-4 pb-20">
        <!-- Post Image/Video Section -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden mb-4">
            @if($post->isImagePost() && $post->media_url)
                <div class="media-container" data-media-type="image">
                    <img
                        src="{{ $post->media_url }}"
                        alt="Post image"
                        class="w-full h-auto media-content"
                        onload="adjustCardSize(this)"
                    >
                </div>
            @elseif($post->isVideoPost() && $post->media_url)
                <div class="media-container" data-media-type="video">
                    <video
                        controls
                        muted
                        loop
                        playsinline
                        preload="metadata"
                        class="w-full h-auto video-autoplay media-content"
                        data-post-id="{{ $post->id }}"
                        onloadedmetadata="adjustCardSize(this)"
                    >
                        <source src="{{ $post->media_url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @else
                <!-- Text-only post -->
                <div class="p-8 text-center bg-gradient-to-br from-blue-50 to-purple-50 dark:from-zinc-800 dark:to-zinc-700">
                    <div class="text-6xl mb-4">📝</div>
                    <p class="text-zinc-600 dark:text-zinc-300 text-lg">{{ $post->content }}</p>
                </div>
            @endif
        </div>

        <!-- Content/Description Section -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4 mb-4">
            <!-- Post Header -->
            <div class="flex items-center justify-between mb-4">
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

            <!-- Post Actions -->
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
            <p class="text-sm font-semibold text-zinc-900 dark:text-white mb-3">
                {{ number_format($likesCount) }} {{ Str::plural('like', $likesCount) }}
            </p>

            <!-- Caption -->
            @if($post->content)
                <div class="text-sm text-zinc-900 dark:text-white">
                    <span class="font-semibold">{{ $post->user->username ?? $post->user->name }}</span>
                    <span class="ml-2">{{ $post->content }}</span>
                </div>
            @endif
        </div>

        <!-- Comments Section -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 mb-4">
            <!-- Comments Toggle -->
            @if($post->comments_count > 0)
                <div class="px-4 py-3 border-b border-zinc-200 dark:border-zinc-700">
                    <button
                        wire:click="toggleComments"
                        class="text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300"
                    >
                        View all {{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}
                    </button>
                </div>
            @endif

            <!-- Comments List -->
            @if($showComments)
                <div class="px-4 py-3 space-y-3 max-h-96 overflow-y-auto comments-scroll">
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
        </div>
    </div>

    <!-- Floating Comment Input -->
    <div class="fixed bottom-0 left-0 right-0 z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border-t border-zinc-200 dark:border-zinc-700">
        <div class="max-w-4xl mx-auto px-4 py-3">
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
                        placeholder="Input comment..."
                        class="w-full bg-zinc-100 dark:bg-zinc-800 text-sm text-zinc-900 dark:text-white placeholder-zinc-500 dark:placeholder-zinc-400 focus:outline-none border-none rounded-full px-4 py-2"
                    >
                </div>
                @if($newComment)
                    <button
                        wire:click="addComment"
                        class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-full transition-colors"
                    >
                        Post
                    </button>
                @endif
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

    /* Media container responsive sizing for vertical layout */
    .media-container {
        position: relative;
        overflow: hidden;
        width: 100%;
        max-height: 70vh;
        min-height: 300px;
    }

    .media-content {
        display: block;
        width: 100%;
        height: auto;
        max-height: 70vh;
        object-fit: cover;
    }

    /* Video specific styling */
    .media-container[data-media-type="video"] .media-content {
        object-fit: contain;
    }

    /* Portrait media - taller card */
    .media-container[data-aspect="portrait"] {
        max-height: 80vh;
    }

    .media-container[data-aspect="portrait"] .media-content {
        max-height: 80vh;
        width: auto;
        margin: 0 auto;
        object-fit: contain;
    }

    /* Landscape media - wider card with proper sizing */
    .media-container[data-aspect="landscape"] {
        max-height: 60vh;
        min-height: 300px;
        width: 100%;
    }

    .media-container[data-aspect="landscape"] .media-content {
        max-height: 60vh;
        min-height: 300px;
        width: 100%;
        object-fit: cover;
    }

    /* Square media */
    .media-container[data-aspect="square"] {
        max-height: 60vh;
    }

    .media-container[data-aspect="square"] .media-content {
        max-height: 60vh;
        width: 100%;
    }

    /* Mobile responsive */
    @media (max-width: 768px) {
        .media-container {
            max-height: 50vh;
            min-height: 250px;
        }

        .media-content {
            max-height: 50vh;
        }

        /* Adjust floating comment input for mobile */
        .fixed.bottom-0 {
            padding-bottom: env(safe-area-inset-bottom);
        }
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

    /* Comments section styling */
    .comments-scroll {
        max-height: 24rem; /* 384px */
    }

    /* Custom scrollbar for comments */
    .comments-scroll::-webkit-scrollbar {
        width: 4px;
    }

    .comments-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .comments-scroll::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 2px;
    }

    .comments-scroll::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    .dark .comments-scroll::-webkit-scrollbar-thumb {
        background: #4b5563;
    }

    .dark .comments-scroll::-webkit-scrollbar-thumb:hover {
        background: #6b7280;
    }

    /* Floating comment input styling */
    .fixed.bottom-0 {
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    /* Ensure content doesn't get hidden behind floating input */
    .pb-20 {
        padding-bottom: 5rem; /* 80px */
    }

    /* Safe area for devices with home indicators */
    @supports (padding-bottom: env(safe-area-inset-bottom)) {
        .fixed.bottom-0 {
            padding-bottom: calc(0.75rem + env(safe-area-inset-bottom));
        }

        .pb-20 {
            padding-bottom: calc(5rem + env(safe-area-inset-bottom));
        }
    }
</style>

<script>
function adjustCardSize(mediaElement) {
    const container = mediaElement.closest('.media-container');

    if (!container) {
        return;
    }

    // Get dimensions - handle both images and videos
    let width, height;
    if (mediaElement.naturalWidth && mediaElement.naturalHeight) {
        // Image
        width = mediaElement.naturalWidth;
        height = mediaElement.naturalHeight;
    } else if (mediaElement.videoWidth && mediaElement.videoHeight) {
        // Video
        width = mediaElement.videoWidth;
        height = mediaElement.videoHeight;
    } else {
        // Fallback - use offset dimensions
        width = mediaElement.offsetWidth;
        height = mediaElement.offsetHeight;

        if (!width || !height) {
            return;
        }
    }

    const aspectRatio = width / height;

    // Remove existing aspect classes
    container.classList.remove('portrait', 'landscape', 'square');

    // Determine aspect ratio and apply appropriate class
    if (aspectRatio > 1.2) {
        // Landscape
        container.setAttribute('data-aspect', 'landscape');
        container.classList.add('landscape');
    } else if (aspectRatio < 0.8) {
        // Portrait
        container.setAttribute('data-aspect', 'portrait');
        container.classList.add('portrait');
    } else {
        // Square
        container.setAttribute('data-aspect', 'square');
        container.classList.add('square');
    }

    // For vertical layout, we don't need to adjust card height
    // The media will fit within the container constraints
}

// For video elements
function adjustVideoCardSize(videoElement) {
    if (videoElement.readyState >= 1) {
        // Video metadata already loaded
        adjustCardSize(videoElement);
    } else {
        videoElement.addEventListener('loadedmetadata', function() {
            adjustCardSize(videoElement);
        });
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Handle existing images
    const images = document.querySelectorAll('img.media-content');
    images.forEach(img => {
        if (img.complete) {
            adjustCardSize(img);
        } else {
            img.addEventListener('load', () => adjustCardSize(img));
        }
    });

    // Handle videos
    const videos = document.querySelectorAll('video.media-content');
    videos.forEach(video => {
        adjustVideoCardSize(video);
    });
});

// Handle window resize
window.addEventListener('resize', function() {
    const mediaElements = document.querySelectorAll('img.media-content, video.media-content');
    mediaElements.forEach(media => {
        adjustCardSize(media);
    });
});

// Livewire event listeners for dynamic content
document.addEventListener('livewire:load', function() {
    // Re-initialize when Livewire updates
    setTimeout(() => {
        const images = document.querySelectorAll('img.media-content');
        images.forEach(img => {
            if (img.complete) {
                adjustCardSize(img);
            } else {
                img.addEventListener('load', () => adjustCardSize(img));
            }
        });

        const videos = document.querySelectorAll('video.media-content');
        videos.forEach(video => {
            adjustVideoCardSize(video);
        });
    }, 100);
});

// Also listen for Livewire updates
document.addEventListener('livewire:update', function() {
    setTimeout(() => {
        const images = document.querySelectorAll('img.media-content');
        images.forEach(img => {
            if (img.complete) {
                adjustCardSize(img);
            } else {
                img.addEventListener('load', () => adjustCardSize(img));
            }
        });

        const videos = document.querySelectorAll('video.media-content');
        videos.forEach(video => {
            adjustVideoCardSize(video);
        });
    }, 100);
});

// Auto-scroll to comments when they're toggled
document.addEventListener('livewire:update', function() {
    // Check if comments section is now visible
    const commentsSection = document.querySelector('.bg-white.dark\\:bg-zinc-800.rounded-xl.shadow-sm.border.border-zinc-200.dark\\:border-zinc-700.mb-4');
    if (commentsSection && commentsSection.querySelector('.comments-scroll')) {
        // Smooth scroll to comments section
        setTimeout(() => {
            commentsSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }, 100);
    }
});
</script>
</div>
