@extends('errors.layout')

@section('title', 'Service Unavailable')

@section('content')
<div class="text-center">
    <!-- Error Code -->
    <div class="mb-8">
        <h1 class="text-9xl font-bold text-zinc-200 dark:text-zinc-700 mb-4">503</h1>
        <div class="w-24 h-1 bg-gradient-to-r from-gray-500 via-blue-500 to-indigo-500 mx-auto rounded-full"></div>
    </div>

    <!-- Error Message -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-zinc-900 dark:text-white mb-4">
            {{ __('Service Unavailable') }}
        </h2>
        <p class="text-lg text-zinc-600 dark:text-zinc-400 mb-6 max-w-md mx-auto">
            {{ __('We are currently performing maintenance. Please check back in a few minutes.') }}
        </p>
    </div>

    <!-- Illustration -->
    <div class="mb-8">
        <div class="relative w-64 h-64 mx-auto mb-6">
            <!-- Background Circle -->
            <div class="absolute inset-0 bg-gradient-to-br from-gray-100 to-blue-100 dark:from-gray-900/20 dark:to-blue-900/20 rounded-full"></div>

            <!-- Tools Icon -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="relative">
                    <!-- Wrench -->
                    <svg class="w-24 h-24 text-zinc-400 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>

                    <!-- Clock Icon -->
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                            <polyline points="12,6 12,12 16,14" stroke-width="2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Floating Elements -->
            <div class="absolute top-4 left-4 w-3 h-3 bg-gray-400 rounded-full animate-pulse"></div>
            <div class="absolute top-8 right-8 w-2 h-2 bg-blue-400 rounded-full animate-pulse delay-1000"></div>
            <div class="absolute bottom-6 left-8 w-2 h-2 bg-indigo-400 rounded-full animate-pulse delay-500"></div>
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
            {{ __('Try Again') }}
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
            {{ __('We are working hard to improve your experience.') }}
            <br>
            {{ __('Thank you for your patience!') }}
        </p>
    </div>
</div>
@endsection
