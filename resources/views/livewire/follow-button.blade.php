<div class="flex items-center space-x-2">
    @auth
        @if(auth()->user()->id !== $user->id)
            <button
                wire:click="toggleFollow"
                class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors duration-200 {{ $isFollowing ? 'bg-zinc-200 dark:bg-zinc-700 text-zinc-800 dark:text-zinc-200 hover:bg-zinc-300 dark:hover:bg-zinc-600' : 'bg-blue-600 text-white hover:bg-blue-700' }}"
            >
                {{ $isFollowing ? 'Following' : 'Follow' }}
            </button>
        @endif
    @else
        <a
            href="{{ route('login') }}"
            class="px-3 py-1.5 text-xs font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200"
        >
            Follow
        </a>
    @endauth
</div>
