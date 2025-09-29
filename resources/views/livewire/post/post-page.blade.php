    <div class="max-w-2xl mx-auto px-4 py-6">
        <!-- Create Post Component -->
        @livewire('post.create-post')

        <!-- Posts List -->
        <div class="space-y-6">
            @forelse($posts as $post)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <!-- Post Header -->
                    <div class="p-6 pb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                {{ $post->user->initials() }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $post->user->name }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $post->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Post Content -->
                    @if($post->content)
                        <div class="px-6 pb-4">
                            <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $post->content }}</p>
                        </div>
                    @endif

                    <!-- Post Media -->
                    @if($post->isImagePost())
                        <div class="px-6 pb-4">
                            <img src="{{ $post->media_url }}" alt="Post image" class="w-full rounded-xl object-cover">
                        </div>
                    @elseif($post->isVideoPost())
                        <div class="px-6 pb-4">
                            <video controls class="w-full rounded-xl">
                                <source src="{{ $post->media_url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @endif

                    <!-- Post Actions -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-6">
                            <button class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="text-sm font-medium">{{ $post->likes_count }}</span>
                            </button>

                            <button class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span class="text-sm font-medium">{{ $post->comments_count }}</span>
                            </button>

                            <button class="flex items-center space-x-2 text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                                <span class="text-sm font-medium">Share</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Belum ada post</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan membuat post pertama Anda!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
