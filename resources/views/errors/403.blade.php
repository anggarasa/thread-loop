@extends('errors.layout')

@section('title', 'Access Forbidden')

@section('content')
<div class="text-center">
    <!-- Error Code -->
    <div class="mb-6">
        <h1 class="text-7xl font-bold text-zinc-200 dark:text-zinc-700 mb-3">403</h1>
        <div class="w-20 h-1 bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 mx-auto rounded-full"></div>
    </div>

    <!-- Error Message -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-3">
            {{ __('Access Forbidden') }}
        </h2>
        <p class="text-base text-zinc-600 dark:text-zinc-400 mb-4 max-w-md mx-auto">
            {{ __('You do not have permission to access this resource. Please check your credentials or contact an administrator.') }}
        </p>
    </div>

    <!-- Illustration -->
    <div class="mb-6">
        <div class="relative w-48 h-48 mx-auto mb-4">
            <!-- Background Circle -->
            <div class="absolute inset-0 bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/20 dark:to-pink-900/20 rounded-full"></div>

            <!-- Lock Icon -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="relative">
                    <!-- Lock -->
                    <svg class="w-16 h-16 text-zinc-400 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke-width="2"/>
                        <circle cx="12" cy="7" r="4" stroke-width="2"/>
                    </svg>

                    <!-- Stop Sign -->
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Floating Elements -->
            <div class="absolute top-4 left-4 w-3 h-3 bg-purple-400 rounded-full animate-pulse"></div>
            <div class="absolute top-8 right-8 w-2 h-2 bg-pink-400 rounded-full animate-pulse delay-1000"></div>
            <div class="absolute bottom-6 left-8 w-2 h-2 bg-red-400 rounded-full animate-pulse delay-500"></div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-3 sm:space-y-0 sm:space-x-3 sm:flex sm:justify-center">
        @auth
            <flux:button
                variant="primary"
                :href="route('homePage')"
                wire:navigate
                class="w-full sm:w-auto px-6 py-2 text-sm font-medium">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                {{ __('Go to Dashboard') }}
            </flux:button>
        @else
            <flux:button
                variant="primary"
                :href="route('login')"
                wire:navigate
                class="w-full sm:w-auto px-6 py-2 text-sm font-medium">
                <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                {{ __('Sign In') }}
            </flux:button>
        @endauth

        <flux:button
            variant="outline"
            :href="route('guest.home')"
            wire:navigate
            class="w-full sm:w-auto px-6 py-2 text-sm font-medium">
            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            {{ __('Go Home') }}
        </flux:button>
    </div>

    <!-- Help Text -->
    <div class="mt-8 text-center">
        <p class="text-sm text-zinc-500 dark:text-zinc-500">
            {{ __('Need access?') }}
            @auth
                {{ __('Contact your administrator or') }}
                <flux:link
                    :href="route('settings.profile')"
                    wire:navigate
                    class="text-accent hover:text-accent/80 transition-colors">
                    {{ __('check your account settings') }}
                </flux:link>
            @else
                {{ __('Please') }}
                <flux:link
                    :href="route('login')"
                    wire:navigate
                    class="text-accent hover:text-accent/80 transition-colors">
                    {{ __('sign in') }}
                </flux:link>
                {{ __('to access this resource') }}
            @endauth
        </p>
    </div>
</div>
@endsection
