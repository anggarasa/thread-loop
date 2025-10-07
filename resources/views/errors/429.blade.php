@extends('errors.layout')

@section('title', 'Too Many Requests')

@section('content')
<div class="text-center">
    <!-- Error Code -->
    <div class="mb-8">
        <h1 class="text-9xl font-bold text-zinc-200 dark:text-zinc-700 mb-4">429</h1>
        <div class="w-24 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 mx-auto rounded-full"></div>
    </div>

    <!-- Error Message -->
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-zinc-900 dark:text-white mb-4">
            {{ __('Too Many Requests') }}
        </h2>
        <p class="text-lg text-zinc-600 dark:text-zinc-400 mb-6 max-w-md mx-auto">
            {{ __('You have made too many requests in a short period. Please wait a moment before trying again.') }}
        </p>
    </div>

    <!-- Illustration -->
    <div class="mb-8">
        <div class="relative w-64 h-64 mx-auto mb-6">
            <!-- Background Circle -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-full"></div>

            <!-- Speedometer Icon -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="relative">
                    <!-- Speedometer -->
                    <svg class="w-24 h-24 text-zinc-400 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83" stroke-width="2"/>
                        <circle cx="12" cy="12" r="3" stroke-width="2"/>
                    </svg>

                    <!-- Stop Icon -->
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Floating Elements -->
            <div class="absolute top-4 left-4 w-3 h-3 bg-indigo-400 rounded-full animate-pulse"></div>
            <div class="absolute top-8 right-8 w-2 h-2 bg-purple-400 rounded-full animate-pulse delay-1000"></div>
            <div class="absolute bottom-6 left-8 w-2 h-2 bg-pink-400 rounded-full animate-pulse delay-500"></div>
        </div>
    </div>

    <!-- Countdown Timer -->
    <div class="mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full mb-4">
            <span id="countdown" class="text-2xl font-bold text-white">60</span>
        </div>
        <p class="text-sm text-zinc-500 dark:text-zinc-500">
            {{ __('Please wait') }} <span id="countdown-text">{{ __('60 seconds') }}</span> {{ __('before trying again') }}
        </p>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center">
        <flux:button
            variant="primary"
            :href="route('guest.home')"
            wire:navigate
            class="w-full sm:w-auto px-8 py-3 text-base font-medium">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            {{ __('Go Home') }}
        </flux:button>

        <flux:button
            variant="outline"
            onclick="history.back()"
            class="w-full sm:w-auto px-8 py-3 text-base font-medium">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            {{ __('Go Back') }}
        </flux:button>
    </div>

    <!-- Help Text -->
    <div class="mt-12 text-center">
        <p class="text-sm text-zinc-500 dark:text-zinc-500">
            {{ __('Rate limiting helps protect our servers.') }}
            <br>
            {{ __('Please be patient and try again in a moment.') }}
        </p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let countdown = 60;
    const countdownElement = document.getElementById('countdown');
    const countdownTextElement = document.getElementById('countdown-text');

    const timer = setInterval(function() {
        countdown--;
        countdownElement.textContent = countdown;

        if (countdown === 1) {
            countdownTextElement.textContent = '{{ __("1 second") }}';
        } else {
            countdownTextElement.textContent = countdown + ' {{ __("seconds") }}';
        }

        if (countdown <= 0) {
            clearInterval(timer);
            countdownElement.textContent = '0';
            countdownTextElement.textContent = '{{ __("0 seconds") }}';

            // Enable refresh button or auto-refresh
            setTimeout(function() {
                window.location.reload();
            }, 1000);
        }
    }, 1000);
});
</script>
@endsection
