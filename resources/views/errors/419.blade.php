@extends('errors.layout')

@section('title', 'Session Expired')

@section('content')
<div class="text-center">
    <!-- Error Code -->
    <div class="mb-8">
        <h1 class="text-9xl font-bold text-zinc-200 dark:text-zinc-700 mb-4">419</h1>
        <div class="w-24 h-1 bg-gradient-to-r from-yellow-500 via-orange-500 to-red-500 mx-auto rounded-full"></div>
    </div>

    <!-- Error Message -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-zinc-900 dark:text-white mb-4">
            {{ __('Session Expired') }}
        </h2>
        <p class="text-lg text-zinc-600 dark:text-zinc-400 mb-6 max-w-md mx-auto">
            {{ __('Your session has expired due to inactivity. Please refresh the page and try again.') }}
        </p>
    </div>

    <!-- Illustration -->
    <div class="mb-8">
        <div class="relative w-64 h-64 mx-auto mb-6">
            <!-- Background Circle -->
            <div class="absolute inset-0 bg-gradient-to-br from-yellow-100 to-orange-100 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-full"></div>

            <!-- Clock Icon -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="relative">
                    <!-- Clock -->
                    <svg class="w-24 h-24 text-zinc-400 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <polyline points="12,6 12,12 16,14" stroke-width="2"/>
                    </svg>

                    <!-- Warning Icon -->
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Floating Elements -->
            <div class="absolute top-4 left-4 w-3 h-3 bg-yellow-400 rounded-full animate-pulse"></div>
            <div class="absolute top-8 right-8 w-2 h-2 bg-orange-400 rounded-full animate-pulse delay-1000"></div>
            <div class="absolute bottom-6 left-8 w-2 h-2 bg-red-400 rounded-full animate-pulse delay-500"></div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
        <flux:button
            variant="primary"
            onclick="window.location.reload()"
            class="w-full sm:w-auto px-8 py-3 text-base font-medium">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            {{ __('Refresh Page') }}
        </flux:button>

        <flux:button
            variant="outline"
            :href="route('guest.home')"
            wire:navigate
            class="w-full sm:w-auto px-8 py-3 text-base font-medium">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            {{ __('Go Home') }}
        </flux:button>
    </div>

    <!-- Help Text -->
    <div class="mt-12 text-center">
        <p class="text-sm text-zinc-500 dark:text-zinc-500">
            {{ __('This usually happens when you leave the page open for too long.') }}
            <br>
            {{ __('Try refreshing the page or') }}
            @auth
                <flux:link
                    :href="route('homePage')"
                    wire:navigate
                    class="text-accent hover:text-accent/80 transition-colors">
                    {{ __('return to your dashboard') }}
                </flux:link>
            @else
                <flux:link
                    :href="route('login')"
                    wire:navigate
                    class="text-accent hover:text-accent/80 transition-colors">
                    {{ __('sign in again') }}
                </flux:link>
            @endauth
        </p>
    </div>
</div>
@endsection
