<div>
    <div wire:poll.10s="check" class="fixed inset-0 pointer-events-none z-[9999]">
        @if($show)
            <div class="absolute bottom-6 right-6 pointer-events-auto"
                 x-data="{ show: true }"
                 x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-full"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-x-0"
                 x-transition:leave-end="opacity-0 transform translate-x-full"
                 x-init="setTimeout(() => { show = false; $wire.set('show', false); }, 5000)">
                <div class="max-w-sm w-[360px] rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 overflow-hidden">
                    <div class="px-4 py-3 flex items-start gap-3">
                        <div class="h-8 w-8 rounded-full bg-blue-600/10 flex items-center justify-center">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-zinc-900 dark:text-white">{{ $message }}</p>
                            <div class="mt-2 flex items-center gap-3">
                                @if($url)
                                    <a href="{{ $url }}" wire:navigate class="text-xs font-semibold text-blue-600 hover:text-blue-700">View</a>
                                @endif
                                <button type="button" wire:click="$set('show', false)" class="text-xs text-zinc-600 dark:text-zinc-400 hover:text-zinc-800 dark:hover:text-zinc-200">Dismiss</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
