<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900" x-data="{ sidebarOpen: false }">
        <!-- Desktop Sidebar -->
        <div class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0 lg:z-50">
            <div class="flex flex-col flex-grow bg-gradient-to-b from-zinc-50 to-zinc-100 dark:from-zinc-900 dark:to-zinc-800 border-r border-zinc-200 dark:border-zinc-700">
                <!-- Logo Section -->
                <div class="px-6 py-4">
                    <a href="{{ route('homePage') }}" class="flex items-center space-x-3" wire:navigate>
                        <x-app-logo />
                        {{-- <span class="text-xl font-bold text-zinc-900 dark:text-zinc-100">ThreadLoop</span> --}}
                    </a>
                </div>

                <!-- Main Navigation -->
                <div class="flex-1 px-4 py-4">
                    <nav class="space-y-2">
                        <!-- Home -->
                        <a href="{{ route('homePage') }}"
                           class="group flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('homePage') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-zinc-700 hover:bg-zinc-200 dark:text-zinc-300 dark:hover:bg-zinc-700' }}"
                           wire:navigate>
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            {{ __('Home') }}
                        </a>

                        <!-- Search -->
                        <a href="{{ route('searchPage') }}"
                           class="group flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('searchPage') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-zinc-700 hover:bg-zinc-200 dark:text-zinc-300 dark:hover:bg-zinc-700' }}"
                           wire:navigate>
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            {{ __('Search') }}
                        </a>

                        <!-- Create Post -->
                        <a href="{{ route('posts.create') }}"
                           class="group flex items-center px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('posts.create') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-zinc-700 hover:bg-zinc-200 dark:text-zinc-300 dark:hover:bg-zinc-700' }}"
                           wire:navigate>
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            {{ __('Create Post') }}
                        </a>

                        <!-- Notifications -->
                        <a href="{{ route('notifications') }}"
                           class="group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('notifications') ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400' : 'text-zinc-700 hover:bg-zinc-200 dark:text-zinc-300 dark:hover:bg-zinc-700' }}"
                           wire:navigate>
                            <div class="flex items-center">
                                <svg class="mr-3 h-5 w-5 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                  </svg>
                                {{ __('Notifications') }}
                            </div>
                            @php($__unread = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0)
                            @if($__unread > 0)
                                <span class="inline-flex items-center justify-center rounded-full bg-red-500 text-white text-xs font-semibold h-5 px-2 min-w-[20px]">
                                    {{ $__unread }}
                                </span>
                            @endif
                        </a>
                    </nav>
                </div>

                <!-- User Menu -->
                <div class="p-4" x-data="{ userMenuOpen: false }">
                    <div class="relative">
                        <button @click="userMenuOpen = !userMenuOpen"
                                class="flex items-center w-full px-3 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-200 dark:text-zinc-300 dark:hover:bg-zinc-700 rounded-xl transition-all duration-200">
                            <div class="flex items-center flex-1">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-3">
                                    <span class="text-white text-sm font-semibold">{{ auth()->user()->initials() }}</span>
                                </div>
                                <div class="flex-1 text-left">
                                    <div class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ auth()->user()->name }}</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ Str::limit(auth()->user()->email, 20, '...') }}</div>
                                </div>
                            </div>
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="userMenuOpen"
                             @click.away="userMenuOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute bottom-full left-0 mb-2 w-56 rounded-lg bg-white dark:bg-zinc-800 shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <a href="{{ route('profile.show', auth()->user()->username) }}"
                                   class="flex items-center px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-700"
                                   wire:navigate>
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ __('View Profile') }}
                                </a>
                                <a href="{{ route('settings.profile') }}"
                                   class="flex items-center px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-700"
                                   wire:navigate>
                                    <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ __('Settings') }}
                                </a>
                                <div class="border-t border-zinc-200 dark:border-zinc-700"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:ml-64">
            {{ $slot }}
        </div>

        <!-- Mobile Bottom Navigation -->
        <div class="fixed bottom-0 left-0 right-0 lg:hidden bg-white dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-700 z-50">
            <div class="grid grid-cols-5 h-16">
                <!-- Home -->
                <a href="{{ route('homePage') }}"
                   class="flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('homePage') ? 'text-blue-600 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400' }} hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                   wire:navigate>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs font-medium">{{ __('Home') }}</span>
                </a>

                <!-- Search -->
                <a href="{{ route('searchPage') }}"
                   class="flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('searchPage') ? 'text-blue-600 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400' }} hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                   wire:navigate>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="text-xs font-medium">{{ __('Search') }}</span>
                </a>

                <!-- Create Post -->
                <a href="{{ route('posts.create') }}"
                   class="flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('posts.create') ? 'text-blue-600 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400' }} hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                   wire:navigate>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-xs font-medium">{{ __('Create') }}</span>
                </a>

                <!-- Notifications -->
                <a href="{{ route('notifications') }}"
                   class="flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('notifications') ? 'text-blue-600 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400' }} hover:text-blue-600 dark:hover:text-blue-400 transition-colors relative"
                   wire:navigate>
                    <div class="relative">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                          </svg>
                        @php($__unread = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0)
                        @if($__unread > 0)
                            <span class="absolute -top-1 -right-1 inline-flex items-center justify-center rounded-full bg-red-500 text-white text-xs font-semibold h-4 w-4 min-w-[16px]">
                                {{ $__unread > 9 ? '9+' : $__unread }}
                            </span>
                        @endif
                    </div>
                    <span class="text-xs font-medium">{{ __('Alerts') }}</span>
                </a>

                <!-- Profile -->
                <a href="{{ route('profile.show', auth()->user()->username) }}"
                   class="flex flex-col items-center justify-center space-y-1 {{ request()->routeIs('profile.show') ? 'text-blue-600 dark:text-blue-400' : 'text-zinc-600 dark:text-zinc-400' }} hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                   wire:navigate>
                    <div class="w-5 h-5 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <span class="text-white text-xs font-semibold">{{ auth()->user()->initials() }}</span>
                    </div>
                    <span class="text-xs font-medium">{{ __('Profile') }}</span>
                </a>
            </div>
        </div>

        <!-- Add bottom padding for mobile to account for bottom navigation -->
        <div class="pb-16 lg:pb-0"></div>

        @fluxScripts
    </body>
</html>
