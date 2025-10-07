<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <title>{{ $title ?? 'Error' }} - {{ config('app.name') }}</title>
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900">
        <!-- Error Navigation -->
        <nav class="bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('guest.home') }}" class="flex items-center space-x-3" wire:navigate>
                            <x-app-logo />
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="flex items-center space-x-4">
                        <flux:link
                            :href="route('guest.home')"
                            wire:navigate
                            class="text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-accent transition-colors">
                            {{ __('Home') }}
                        </flux:link>
                        @auth
                            <flux:link
                                :href="route('homePage')"
                                wire:navigate
                                class="text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-accent transition-colors">
                                {{ __('Dashboard') }}
                            </flux:link>
                        @else
                            <flux:link
                                :href="route('login')"
                                wire:navigate
                                class="text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-accent transition-colors">
                                {{ __('Log In') }}
                            </flux:link>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Error Content -->
        <main class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="max-w-md w-full space-y-8">
                @yield('content')
            </div>
        </main>

        @fluxScripts
    </body>
</html>
