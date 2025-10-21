@props(['user', 'size' => 'md'])

@php
    $sizeClasses = [
        'xs' => 'h-6 w-6 text-xs',
        'sm' => 'h-8 w-8 text-xs',
        'md' => 'h-10 w-10 text-sm',
        'lg' => 'h-12 w-12 text-base',
        'xl' => 'h-16 w-16 text-lg',
        '2xl' => 'h-20 w-20 text-xl',
    ];

    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div class="{{ $sizeClass }} rounded-full bg-gradient-to-tr from-pink-500 via-red-500 to-yellow-500 p-0.5">
    <div class="h-full w-full rounded-full bg-white dark:bg-zinc-800 p-0.5">
        @if($user->profile_url)
            <img
                src="{{ $user->profile_url }}"
                alt="{{ $user->name }}"
                class="h-full w-full rounded-full object-cover"
                onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'h-full w-full rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center\'><span class=\'font-semibold text-zinc-600 dark:text-zinc-300\'>{{ $user->initials() }}</span></div>';"
            >
        @else
            <div class="h-full w-full rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center">
                <span class="font-semibold text-zinc-600 dark:text-zinc-300">{{ $user->initials() }}</span>
            </div>
        @endif
    </div>
</div>

