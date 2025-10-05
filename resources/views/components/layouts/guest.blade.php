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
                        <a href="{{ route('guest.home') }}"
                           class="text-sm font-medium {{ request()->routeIs('guest.home') ? 'text-blue-600 dark:text-blue-400' : 'text-zinc-700 dark:text-zinc-300 hover:text-blue-600 dark:hover:text-blue-400' }} transition-colors"
                           wire:navigate>
                            {{ __('Home') }}
                        </a>
                        <a href="{{ route('guest.search') }}"
                           class="text-sm font-medium {{ request()->routeIs('guest.search') ? 'text-blue-600 dark:text-blue-400' : 'text-zinc-700 dark:text-zinc-300 hover:text-blue-600 dark:hover:text-blue-400' }} transition-colors"
                           wire:navigate>
                            {{ __('Search') }}
                        </a>
                    </div>

                    <!-- Auth Links -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}"
                           class="text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                           wire:navigate>
                            {{ __('Log In') }}
                        </a>
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 transition-colors"
                           wire:navigate>
                            {{ __('Sign Up') }}
                        </a>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button type="button"
                                class="text-zinc-700 dark:text-zinc-300 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:text-blue-600 dark:focus:text-blue-400"
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
                        <a href="{{ route('guest.home') }}"
                           class="block px-3 py-2 text-base font-medium {{ request()->routeIs('guest.home') ? 'text-blue-600 dark:text-blue-400' : 'text-zinc-700 dark:text-zinc-300 hover:text-blue-600 dark:hover:text-blue-400' }} transition-colors"
                           wire:navigate>
                            {{ __('Home') }}
                        </a>
                        <a href="{{ route('guest.search') }}"
                           class="block px-3 py-2 text-base font-medium {{ request()->routeIs('guest.search') ? 'text-blue-600 dark:text-blue-400' : 'text-zinc-700 dark:text-zinc-300 hover:text-blue-600 dark:hover:text-blue-400' }} transition-colors"
                           wire:navigate>
                            {{ __('Search') }}
                        </a>
                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4">
                            <a href="{{ route('login') }}"
                               class="block px-3 py-2 text-base font-medium text-zinc-700 dark:text-zinc-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                               wire:navigate>
                                {{ __('Log In') }}
                            </a>
                            <a href="{{ route('register') }}"
                               class="block px-3 py-2 text-base font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-500 transition-colors"
                               wire:navigate>
                                {{ __('Sign Up') }}
                            </a>
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
