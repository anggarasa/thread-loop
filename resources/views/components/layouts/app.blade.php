<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        @livewire('notifications.realtime-notifier')
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
