<div class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
    <div class="max-w-4xl mx-auto px-4 py-6">
        <!-- Profile Header -->
        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-6 mb-6">
            <div class="flex items-start space-x-6">
                <!-- Profile Picture -->
                <div class="h-24 w-24 rounded-full bg-gradient-to-tr from-pink-500 via-red-500 to-yellow-500 p-0.5">
                    <div class="h-full w-full rounded-full bg-white dark:bg-zinc-800 p-0.5">
                        @if($user->profile_url)
                            <img src="{{ $user->profile_url }}" alt="{{ $user->name }}" class="h-full w-full rounded-full object-cover">
                        @else
                            <div class="h-full w-full rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                                <span class="text-2xl font-semibold text-zinc-600 dark:text-zinc-300">{{ $user->initials() }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $user->name }}</h1>
                            <p class="text-zinc-500 dark:text-zinc-400">@<span>{{ $user->username }}</span></p>
                        </div>
                        <div class="flex items-center space-x-3">
                            @if(auth()->check() && auth()->id() === $user->id)
                                <!-- Settings Button -->
                                <a href="{{ route('settings.profile') }}" wire:navigate
                                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 bg-white dark:bg-zinc-700 border border-zinc-300 dark:border-zinc-600 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Settings
                                </a>

                                <!-- Logout Button -->
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 bg-white dark:bg-zinc-700 border border-red-300 dark:border-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            @else
                                @livewire('follow-button', ['user' => $user])
                            @endif
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center space-x-6 mb-4">
                        <div class="text-center">
                            <div class="text-xl font-bold text-zinc-900 dark:text-white">{{ $posts->count() }}</div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">Posts</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-zinc-900 dark:text-white">{{ $followersCount }}</div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">Followers</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-zinc-900 dark:text-white">{{ $followingCount }}</div>
                            <div class="text-sm text-zinc-500 dark:text-zinc-400">Following</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs (only show for own profile) -->
        @if(auth()->check() && auth()->id() === $user->id)
            <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-700 mb-6">
                <div class="flex">
                    <button
                        wire:click="switchTab('posts')"
                        class="flex-1 px-6 py-4 text-center font-medium transition-colors {{ $activeTab === 'posts' ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300' }}"
                    >
                        Posts
                    </button>
                    <button
                        wire:click="switchTab('liked')"
                        class="flex-1 px-6 py-4 text-center font-medium transition-colors {{ $activeTab === 'liked' ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300' }}"
                    >
                        Liked
                    </button>
                    <button
                        wire:click="switchTab('saved')"
                        class="flex-1 px-6 py-4 text-center font-medium transition-colors {{ $activeTab === 'saved' ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400' : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-300' }}"
                    >
                        Saved
                    </button>
                </div>
            </div>
        @endif

        <!-- Posts Grid -->
        <div class="grid grid-cols-3 gap-1">
            @if($activeTab === 'posts')
                @forelse($posts as $post)
                    <a href="{{ route('posts.show', $post) }}" wire:navigate class="aspect-square bg-zinc-200 dark:bg-zinc-700">
                        @if($post->isImagePost() && $post->media_url)
                            <img src="{{ $post->media_url }}" alt="Post" class="w-full h-full object-cover">
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
                    <div class="col-span-3 text-center py-12">
                        <p class="text-zinc-500 dark:text-zinc-400">No posts yet</p>
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
                    <div class="col-span-3 text-center py-12">
                        <p class="text-zinc-500 dark:text-zinc-400">No saved posts yet</p>
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
                    <div class="col-span-3 text-center py-12">
                        <p class="text-zinc-500 dark:text-zinc-400">No liked posts yet</p>
                    </div>
                @endforelse
            @endif
        </div>
    </div>
</div>
