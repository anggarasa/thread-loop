<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900">
        <!-- Guest Navigation -->
        <nav class="bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700" x-data="{ mobileMenuOpen: false }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('guest.home') }}" class="flex items-center space-x-3" wire:navigate>
                            <x-app-logo />
                        </a>
                    </div>

                    <!-- Desktop Navigation Links -->
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

                    <!-- Desktop Auth Links -->
                    <div class="hidden md:flex items-center space-x-4">
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
                                class="inline-flex items-center justify-center p-2 rounded-md text-zinc-700 dark:text-zinc-300 hover:text-accent hover:bg-zinc-100 dark:hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-accent transition-colors"
                                @click="mobileMenuOpen = !mobileMenuOpen"
                                :aria-expanded="mobileMenuOpen">
                            <span class="sr-only">Open main menu</span>
                            <!-- Hamburger icon -->
                            <svg class="h-6 w-6" x-show="!mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <!-- Close icon -->
                            <svg class="h-6 w-6" x-show="mobileMenuOpen" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div class="md:hidden" x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 border-t border-zinc-200 dark:border-zinc-700">
                        <flux:link
                            :href="route('guest.home')"
                            wire:navigate
                            class="block px-3 py-2 text-base font-medium {{ request()->routeIs('guest.home') ? 'text-accent bg-accent/10' : 'text-zinc-700 dark:text-zinc-300 hover:text-accent hover:bg-zinc-100 dark:hover:bg-zinc-800' }} transition-colors rounded-md"
                            @click="mobileMenuOpen = false">
                            {{ __('Home') }}
                        </flux:link>
                        <flux:link
                            :href="route('guest.search')"
                            wire:navigate
                            class="block px-3 py-2 text-base font-medium {{ request()->routeIs('guest.search') ? 'text-accent bg-accent/10' : 'text-zinc-700 dark:text-zinc-300 hover:text-accent hover:bg-zinc-100 dark:hover:bg-zinc-800' }} transition-colors rounded-md"
                            @click="mobileMenuOpen = false">
                            {{ __('Search') }}
                        </flux:link>

                        <!-- Mobile Auth Links -->
                        <div class="border-t border-zinc-200 dark:border-zinc-700 pt-4 mt-4">
                            <flux:link
                                :href="route('login')"
                                wire:navigate
                                class="block px-3 py-2 text-base font-medium text-zinc-700 dark:text-zinc-300 hover:text-accent hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors rounded-md"
                                @click="mobileMenuOpen = false">
                                {{ __('Log In') }}
                            </flux:link>
                            <flux:link
                                :href="route('register')"
                                wire:navigate
                                class="block px-3 py-2 text-base font-medium text-white bg-accent hover:bg-accent/90 transition-colors rounded-md mt-2"
                                @click="mobileMenuOpen = false">
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
