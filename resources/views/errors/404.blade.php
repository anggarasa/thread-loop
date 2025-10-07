@extends('errors.layout')

@section('title', 'Page Not Found')

@section('content')
<div class="text-center">
    <!-- Error Code -->
    <div class="mb-6">
        <h1 class="text-7xl font-bold text-zinc-200 dark:text-zinc-700 mb-3">404</h1>
        <div class="w-20 h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 mx-auto rounded-full"></div>
    </div>

    <!-- Error Message -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-3">
            {{ __('Oops! Page Not Found') }}
        </h2>
        <p class="text-base text-zinc-600 dark:text-zinc-400 mb-4 max-w-md mx-auto">
            {{ __('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.') }}
        </p>
    </div>

    <!-- Illustration -->
    <div class="mb-6">
        <div class="relative w-48 h-48 mx-auto mb-4">
            <!-- Background Circle -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/20 dark:to-purple-900/20 rounded-full"></div>

            <!-- Search Icon -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="relative">
                    <!-- Magnifying Glass -->
                    <svg class="w-16 h-16 text-zinc-400 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" stroke-width="2"/>
                        <path d="m21 21-4.35-4.35" stroke-width="2"/>
                    </svg>

                    <!-- Question Mark -->
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-xs">?</span>
                    </div>
                </div>
            </div>

            <!-- Floating Elements -->
            <div class="absolute top-4 left-4 w-3 h-3 bg-blue-400 rounded-full animate-pulse"></div>
            <div class="absolute top-8 right-8 w-2 h-2 bg-purple-400 rounded-full animate-pulse delay-1000"></div>
            <div class="absolute bottom-6 left-8 w-2 h-2 bg-pink-400 rounded-full animate-pulse delay-500"></div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-3 sm:space-y-0 sm:space-x-3 sm:flex sm:justify-center">
        <flux:button
            variant="primary"
            :href="route('guest.home')"
            wire:navigate
            class="w-full sm:w-auto px-6 py-2 text-sm font-medium">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            {{ __('Go Home') }}
        </flux:button>

        <flux:button
            variant="outline"
            onclick="history.back()"
            class="w-full sm:w-auto px-6 py-2 text-sm font-medium">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('Go Back') }}
        </flux:button>
    </div>

    <!-- Help Text -->
    <div class="mt-8 text-center">
        <p class="text-sm text-zinc-500 dark:text-zinc-500">
            {{ __('Need help?') }}
            <flux:link
                :href="route('guest.search')"
                wire:navigate
                class="text-accent hover:text-accent/80 transition-colors">
                {{ __('Try searching') }}
            </flux:link>
            {{ __('or') }}
            @auth
                <flux:link
                    :href="route('homePage')"
                    wire:navigate
                    class="text-accent hover:text-accent/80 transition-colors">
                    {{ __('visit your dashboard') }}
                </flux:link>
            @else
                <flux:link
                    :href="route('login')"
                    wire:navigate
                    class="text-accent hover:text-accent/80 transition-colors">
                    {{ __('sign in') }}
                </flux:link>
            @endauth
        </p>
    </div>
</div>
@endsection
