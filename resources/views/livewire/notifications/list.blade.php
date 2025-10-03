<div class="max-w-3xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold text-zinc-900 dark:text-white">Notifications</h1>
        @if($unreadCount > 0)
            <button wire:click="markAllAsRead" class="text-sm text-blue-600 hover:text-blue-700">Mark all as read ({{ $unreadCount }})</button>
        @endif
    </div>

    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        @forelse($notifications as $notification)
            @php
                $data = $notification->data;
                $isUnread = is_null($notification->read_at);
            @endphp
            <div class="flex items-start gap-3 px-4 py-3 border-b border-zinc-100 dark:border-zinc-700 {{ $isUnread ? 'bg-zinc-50 dark:bg-zinc-900/40' : '' }}">
                <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-blue-500 via-purple-500 to-pink-500 p-0.5 flex-shrink-0">
                    <div class="h-full w-full rounded-full bg-white dark:bg-zinc-800 p-0.5">
                        <div class="h-full w-full rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                            <span class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">{{ substr($data['actor_name'] ?? 'U', 0, 1) }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="text-sm text-zinc-900 dark:text-white">
                        {{ $data['message'] ?? 'Notification' }}
                    </div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if(($data['post_id'] ?? null))
                        <button wire:click="view('{{ $notification->id }}')" class="text-xs text-blue-600 hover:text-blue-700">View</button>
                    @elseif(($data['actor_username'] ?? null))
                        <button wire:click="view('{{ $notification->id }}')" class="text-xs text-blue-600 hover:text-blue-700">View</button>
                    @endif
                    @if($isUnread)
                        <button wire:click="markAsRead('{{ $notification->id }}')" class="text-xs text-zinc-600 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200">Mark as read</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="px-4 py-8 text-center text-zinc-500 dark:text-zinc-400">
                No notifications yet.
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>


