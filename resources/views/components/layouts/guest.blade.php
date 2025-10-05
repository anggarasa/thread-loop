<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900">
        <!-- Guest Navigation -->
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
                    <div class="hidden md:flex items-center space-x-8">
                        <flux:link
                            :href="route('guest.home')"
                            wire:navigate
                            class="text-sm font-medium {{ request()->routeIs('guest.home') ? 'text-accent' : 'text-zinc-700 dark:text-zinc-300 hover:text-accent' }} transition-colors">
                            {{ __('Home') }}
                        </flux:link>
                        <flux:link
                            :href="route('guest.search')"
                            wire:navigate
                            class="text-sm font-medium {{ request()->routeIs('guest.search') ? 'text-accent' : 'text-zinc-700 dark:text-zinc-300 hover:text-accent' }} transition-colors">
                            {{ __('Search') }}
                        </flux:link>
                    </div>

                    <!-- Auth Links -->
                    <div class="flex items-center space-x-4">
                        <flux:link
                            :href="route('login')"
                            wire:navigate
                            class="text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-accent transition-colors">
                            {{ __('Log In') }}
                        </flux:link>
                        <flux:button
                            variant="primary"
                            :href="route('register')"
                            wire:navigate
                            class="px-4 py-2 text-sm font-medium">
                            {{ __('Sign Up') }}
                        </flux:button>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button"
                                class="text-zinc-700 dark:text-zinc-300 hover:text-accent focus:outline-none focus:text-accent"
                                x-data="{ open: false }"
                                @click="open = !open">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div class="md:hidden" x-data="{ open: false }" x-show="open" x-transition>
                    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-zinc-200 dark:border-zinc-700">
                        <flux:link
                            :href="route('guest.home')"
                            wire:navigate
                            class="block px-3 py-2 text-base font-medium {{ request()->routeIs('guest.home') ? 'text-accent' : 'text-zinc-700 dark:text-zinc-300 hover:text-accent' }} transition-colors">
                            {{ __('Home') }}
                        </flux:link>
                        <flux:link
                            :href="route('guest.search')"
                            wire:navigate
                            class="block px-3 py-2 text-base font-medium {{ request()->routeIs('guest.search') ? 'text-accent' : 'text-zinc-700 dark:text-zinc-300 hover:text-accent' }} transition-colors">
                            {{ __('Search') }}
                        </flux:link>
                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                            <flux:link
                                :href="route('login')"
                                wire:navigate
                                class="block px-3 py-2 text-base font-medium text-zinc-700 dark:text-zinc-300 hover:text-accent transition-colors">
                                {{ __('Log In') }}
                            </flux:link>
                            <flux:link
                                :href="route('register')"
                                wire:navigate
                                class="block px-3 py-2 text-base font-medium text-accent hover:text-accent-foreground transition-colors">
                                {{ __('Sign Up') }}
                            </flux:link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="min-h-screen">
            {{ $slot }}
        </main>

        @fluxScripts
    </body>
</html>
