<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <!-- Profile Header -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl sm:rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-4 sm:p-6 mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">
                <!-- Profile Picture -->
                <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-full bg-gradient-to-tr from-pink-500 via-red-500 to-yellow-500 p-0.5 flex-shrink-0">
                    <div class="h-full w-full rounded-full bg-white dark:bg-zinc-800 p-0.5">
                        @if($user->profile_url)
                            <img src="{{ $user->profile_url }}" alt="{{ $user->name }}" class="h-full w-full rounded-full object-cover">
                        @else
                            <div class="h-full w-full rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                <span class="text-lg sm:text-2xl font-semibold text-zinc-600 dark:text-zinc-300">{{ $user->initials() }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="flex-1 w-full">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 space-y-3 sm:space-y-0">
                        <div class="text-center sm:text-left">
                            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900 dark:text-white">{{ $user->name }}</h1>
                            <p class="text-zinc-500 dark:text-zinc-400">@<span>{{ $user->username }}</span></p>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-3">
                            @if(auth()->check() && auth()->id() === $user->id)
                                <!-- Settings Button -->
                                <a href="{{ route('settings.profile') }}" wire:navigate
                                   class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-600 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Settings
                                </a>

                                <!-- Logout Button -->
                                <flux:modal.trigger name="confirm-logout-profile">
                                    <button type="button"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 bg-white dark:bg-zinc-700 border border-red-300 dark:border-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Logout
                                    </button>
                                </flux:modal.trigger>
                            @else
                                <button
                                    wire:click="toggleFollow({{ $user->id }})"
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200 {{ $this->isFollowing($user->id) ? 'bg-zinc-200 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-200 hover:bg-zinc-300 dark:hover:bg-zinc-600' : 'bg-blue-600 text-white hover:bg-blue-700' }}"
                                >
                                    {{ $this->isFollowing($user->id) ? 'Following' : 'Follow' }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center justify-center sm:justify-start space-x-6 sm:space-x-8 mb-4">
                        <div class="text-center">
                            <div class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-white">{{ $posts->count() }}</div>
                            <div class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">Posts</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-white">{{ $followersCount }}</div>
                            <div class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">Followers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg sm:text-xl font-bold text-zinc-900 dark:text-white">{{ $followingCount }}</div>
                            <div class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">Following</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs (only show for own profile) -->
        @if(auth()->check() && auth()->id() === $user->id)
            <div class="bg-white dark:bg-zinc-800 rounded-xl sm:rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-700 mb-4 sm:mb-6">
                <div class="flex">
                    <button
                        wire:click="switchTab('posts')"
                        class="flex-1 px-3 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base font-medium transition-colors {{ $activeTab === 'posts' ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300' }}"
                    >
                        Posts
                    </button>
                    <button
                        wire:click="switchTab('liked')"
                        class="flex-1 px-3 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base font-medium transition-colors {{ $activeTab === 'liked' ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300' }}"
                    >
                        Liked
                    </button>
                    <button
                        wire:click="switchTab('saved')"
                        class="flex-1 px-3 sm:px-6 py-3 sm:py-4 text-center text-sm sm:text-base font-medium transition-colors {{ $activeTab === 'saved' ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300' }}"
                    >
                        Saved
                    </button>
                </div>
            </div>
        @endif

        <!-- Posts Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-1 sm:gap-2">
            @if($activeTab === 'posts')
                @forelse($posts as $post)
                    <div class="relative aspect-square bg-zinc-200 dark:bg-zinc-700 group">
                        <a href="{{ route('posts.show', $post) }}" wire:navigate class="block w-full h-full">
                            @if($post->isImagePost() && $post->media_url)
                                <img src="{{ $post->media_url }}" alt="Post" class="w-full h-full object-cover">
                            @elseif($post->isVideoPost() && $post->media_url)
                                <video class="w-full h-full object-cover">
                                    <source src="{{ $post->media_url }}" type="video/mp4">
                                </video>
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-purple-50 dark:from-zinc-800 dark:to-zinc-700">
                                    <div class="text-center">
                                        <div class="text-4xl mb-2">📝</div>
                                        <p class="text-xs text-zinc-600 dark:text-zinc-300">Text Post</p>
                                    </div>
                                </div>
                            @endif
                        </a>

                        @if($post->user_id === auth()->id())
                            <!-- Delete button overlay -->
                            <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <flux:modal.trigger name="delete-post-{{ $post->id }}">
                                    <button
                                        wire:click="deletePost({{ $post->id }})"
                                        class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-full shadow-lg transition-colors"
                                        title="Delete post"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </flux:modal.trigger>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="col-span-2 sm:col-span-3 text-center py-8 sm:py-12">
                        <p class="text-sm sm:text-base text-zinc-500 dark:text-zinc-400">No posts yet</p>
                    </div>
                @endforelse
            @elseif($activeTab === 'saved')
                @forelse($savedPosts as $post)
                    <a href="{{ route('posts.show', $post) }}" wire:navigate class="aspect-square bg-zinc-200 dark:bg-zinc-700">
                        @if($post->isImagePost() && $post->media_url)
                            <img src="{{ $post->media_url }}" alt="Saved Post" class="w-full h-full object-cover">
                        @elseif($post->isVideoPost() && $post->media_url)
                            <video class="w-full h-full object-cover" muted>
                                <source src="{{ $post->media_url }}" type="video/mp4">
                            </video>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-purple-50 dark:from-zinc-800 dark:to-zinc-700">
                                <div class="text-center">
                                    <div class="text-4xl mb-2">📝</div>
                                    <p class="text-xs text-zinc-600 dark:text-zinc-300">Text Post</p>
                                </div>
                            </div>
                        @endif
                    </a>
                @empty
                    <div class="col-span-2 sm:col-span-3 text-center py-8 sm:py-12">
                        <p class="text-sm sm:text-base text-zinc-500 dark:text-zinc-400">No saved posts yet</p>
                    </div>
                @endforelse
            @elseif($activeTab === 'liked')
                @forelse($likedPosts as $post)
                    <a href="{{ route('posts.show', $post) }}" wire:navigate class="aspect-square bg-zinc-200 dark:bg-zinc-700">
                        @if($post->isImagePost() && $post->media_url)
                            <img src="{{ $post->media_url }}" alt="Liked Post" class="w-full h-full object-cover">
                        @elseif($post->isVideoPost() && $post->media_url)
                            <video class="w-full h-full object-cover" muted>
                                <source src="{{ $post->media_url }}" type="video/mp4">
                            </video>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-purple-50 dark:from-zinc-800 dark:to-zinc-700">
                                <div class="text-center">
                                    <div class="text-4xl mb-2">📝</div>
                                    <p class="text-xs text-zinc-600 dark:text-zinc-300">Text Post</p>
                                </div>
                            </div>
                        @endif
                    </a>
                @empty
                    <div class="col-span-2 sm:col-span-3 text-center py-8 sm:py-12">
                        <p class="text-sm sm:text-base text-zinc-500 dark:text-zinc-400">No liked posts yet</p>
                    </div>
                @endforelse
            @endif
        </div>
    </div>

    <!-- Delete Post Confirmation Modal -->
    @if($postToDelete)
        <flux:modal name="delete-post-{{ $postToDelete }}" class="min-w-[18rem] sm:min-w-[22rem]" :closable="false" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete post?</flux:heading>
                    <flux:text class="mt-2">
                        <p>You're about to delete this post.</p>
                        <p>This action cannot be reversed.</p>
                    </flux:text>
                </div>

                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button wire:click="cancelDeletePost" variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button wire:click="confirmDeletePost" variant="danger">
                        Delete post
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif

    <!-- Confirm Logout Modal (Profile) -->
    <flux:modal name="confirm-logout-profile" class="min-w-[18rem] sm:min-w-[22rem]" :closable="false" :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Logout?</flux:heading>
                <flux:text class="mt-2">
                    <p>You're about to log out of your account.</p>
                    <p>You will need to log in again to continue.</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </flux:modal>
</div>
