@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center space-y-2">
    <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">
        {{ $title }}
    </h1>
    <p class="text-neutral-600 dark:text-neutral-400">
        {{ $description }}
    </p>
</div>
