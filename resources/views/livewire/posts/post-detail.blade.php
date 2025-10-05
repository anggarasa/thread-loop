<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
    <!-- Header with Back Button -->
    <div class="sticky top-0 z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-700">
        <div class="max-w-4xl mx-auto px-4 py-3">
            <div class="flex items-center space-x-4">
                <button onclick="history.back()"
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
                            <a href="{{ route('profile.show', $post->user->username) }}" wire:navigate class="font-semibold text-zinc-900 dark:text-white hover:underline">{{ $post->user->username ?? $post->user->name }}</a>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @livewire('follow-button', ['user' => $post->user])
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
                        <svg class="h-6 w-6 {{ $isLiked ? 'fill-current text-red-500 dark:text-red-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <button
                        wire:click="toggleSave"
                        class="flex items-center space-x-2 text-zinc-600 hover:text-blue-500 dark:text-zinc-400 dark:hover:text-blue-400 transition-colors {{ $isSaved ? 'text-blue-500 dark:text-blue-400' : '' }}"
                    >
                        <svg class="h-6 w-6 {{ $isSaved ? 'fill-current text-blue-500 dark:text-blue-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                        <span class="text-sm font-semibold">{{ $isSaved ? 'Saved' : 'Save' }}</span>
                    </button>
                    <button
                        onclick="copyShareLink({{ $post->id }})"
                        class="flex items-center space-x-2 text-zinc-600 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors"
                        title="Share post"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        <span class="text-sm font-semibold">Share</span>
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

    <script>
        function copyShareLink(postId) {
            const url = `{{ url('/share') }}/${postId}`;
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;

            // Try modern clipboard API first (requires HTTPS)
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(function() {
                    showSuccessMessage(button, originalHTML);
                    fetch(`{{ url('/share') }}/${postId}/copied`, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
                }).catch(function(err) {
                    console.error('Clipboard API failed:', err);
                    fallbackCopyTextToClipboard(url, button, originalHTML);
                });
            } else {
                // Fallback for HTTP or older browsers
                fallbackCopyTextToClipboard(url, button, originalHTML);
            }
        }

        function fallbackCopyTextToClipboard(text, button, originalHTML) {
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
                    fetch(`{{ url('/share') }}/${postId}/copied`, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });
                } else {
                    showErrorMessage(button, originalHTML, text);
                }
            } catch (err) {
                console.error('Fallback copy failed:', err);
                showErrorMessage(button, originalHTML, text);
            }

            document.body.removeChild(textArea);
        }

        function showSuccessMessage(button, originalHTML) {
            button.innerHTML = `
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-sm font-semibold">Copied!</span>
            `;
            button.classList.add('text-green-600', 'dark:text-green-400');
            button.title = 'Link copied!';

            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('text-green-600', 'dark:text-green-400');
                button.title = 'Share post';
            }, 2000);
        }

        function showErrorMessage(button, originalHTML, url) {
            button.innerHTML = `
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <span class="text-sm font-semibold">Click to copy</span>
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
                        copyShareLink(button.dataset.postId || button.closest('[onclick]').onclick.toString().match(/copyShareLink\((\d+)\)/)[1]);
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
