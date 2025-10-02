<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $post->user->username ?? $post->user->name }} - Thread Loop</title>

    <!-- Meta tags for social sharing -->
    <meta property="og:title" content="{{ $post->user->username ?? $post->user->name }} on Thread Loop">
    <meta property="og:description" content="{{ Str::limit($post->content, 160) }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ route('posts.share', $post) }}">
    @if($post->media_url)
        <meta property="og:image" content="{{ $post->media_url }}">
    @endif

    <!-- Twitter Card meta tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $post->user->username ?? $post->user->name }} on Thread Loop">
    <meta name="twitter:description" content="{{ Str::limit($post->content, 160) }}">
    @if($post->media_url)
        <meta name="twitter:image" content="{{ $post->media_url }}">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
        <!-- Header -->
        <div class="sticky top-0 z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-700">
            <div class="max-w-4xl mx-auto px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <img src="{{ asset('assets/images/logo-ThreadLoop2-aplikasi.svg') }}" alt="Thread Loop" class="h-8 w-8">
                            <h1 class="text-lg font-semibold text-zinc-900 dark:text-white">Thread Loop</h1>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('home') }}" class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-colors">
                            Home
                        </a>
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-neutral-800 hover:bg-neutral-600 text-white rounded-full text-sm font-semibold transition-colors">
                            Login
                        </a>
                    </div>
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
                                class="w-full h-full"
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

                <!-- Bottom Section: Content Area -->
                <div class="p-6 space-y-6">
                    <!-- Post Header -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-pink-500 via-red-500 to-yellow-500 p-0.5">
                                <div class="h-full w-full rounded-full bg-white dark:bg-zinc-800 p-0.5">
                                    <div class="h-full w-full rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                        <span class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">{{ $post->user ? $post->user->initials() : '?' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h2 class="font-semibold text-zinc-900 dark:text-white">{{ $post->user ? ($post->user->username ?? $post->user->name) : 'Unknown User' }}</h2>
                                <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <button
                            onclick="copyShareLink()"
                            class="flex items-center space-x-2 px-4 py-2 bg-zinc-100 dark:bg-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-600 text-zinc-700 dark:text-zinc-300 rounded-full text-sm font-semibold transition-colors"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                            <span>Share</span>
                        </button>
                    </div>

                    <!-- Post Content -->
                    @if($post->content)
                        <div class="text-zinc-900 dark:text-white mb-4">
                            <p class="text-lg leading-relaxed">{{ $post->content }}</p>
                        </div>
                    @endif

                    <!-- Post Stats -->
                    <div class="flex items-center space-x-6 mb-4">
                        <div class="flex items-center space-x-2 text-zinc-600 dark:text-zinc-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="text-sm font-semibold">{{ number_format($post->likes_count) }} {{ Str::plural('like', $post->likes_count) }}</span>
                        </div>
                        <div class="flex items-center space-x-2 text-zinc-600 dark:text-zinc-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span class="text-sm font-semibold">{{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}</span>
                        </div>
                    </div>

                    <!-- Call to Action -->
                    <div class="bg-gradient-to-r from-neutral-50 to-gray-50 dark:from-zinc-800 dark:to-zinc-700 rounded-2xl p-6 text-center">
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Join Thread Loop</h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">
                            Create an account to like, comment, and share your own posts!
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('register') }}" class="px-6 py-3 bg-neutral-800 hover:bg-neutral-600 text-white rounded-full text-sm font-semibold transition-colors">
                                Create Account
                            </a>
                            <a href="{{ route('login') }}" class="px-6 py-3 bg-white dark:bg-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-600 text-zinc-900 dark:text-white border border-zinc-200 dark:border-zinc-600 rounded-full text-sm font-semibold transition-colors">
                                Sign In
                            </a>
                        </div>
                    </div>
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

        function copyShareLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(function() {
                // Show success message
                const button = event.target.closest('button');
                const originalText = button.innerHTML;
                button.innerHTML = `
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Copied!</span>
                `;
                button.classList.add('text-green-600', 'dark:text-green-400');

                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('text-green-600', 'dark:text-green-400');
                }, 2000);
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
                alert('Could not copy link. Please copy manually: ' + url);
            });
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

</body>
</html>
