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
                        @livewire('follow-button', ['user' => $user])
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
            @else
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
            @endif
        </div>
    </div>
</div>
