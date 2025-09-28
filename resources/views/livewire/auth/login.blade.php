<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="text-center space-y-2">
        <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">
            {{ __('Welcome back') }}
        </h1>
        <p class="text-neutral-600 dark:text-neutral-400">
            {{ __('Sign in to your ThreadLoop account') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <!-- Login Form -->
    <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-8 shadow-sm">
        <form method="POST" wire:submit="login" class="flex flex-col gap-6">
            <!-- Email Address -->
            <div class="space-y-2">
                <flux:input
                    wire:model="email"
                    :label="__('Email address')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="Enter your email"
                    class="h-12"
                />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <flux:input
                    wire:model="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                    viewable
                    class="h-12"
                />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <flux:checkbox wire:model="remember" :label="__('Remember me')" />

                @if (Route::has('password.request'))
                    <flux:link
                        class="text-sm text-neutral-600 dark:text-neutral-400 hover:text-neutral-900 dark:hover:text-white transition-colors"
                        :href="route('password.request')"
                        wire:navigate>
                        {{ __('Forgot password?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Submit Button -->
            <flux:button
                variant="primary"
                type="submit"
                class="w-full h-12 text-base font-medium rounded-xl bg-neutral-900 hover:bg-neutral-800 dark:bg-white dark:hover:bg-neutral-100 dark:text-neutral-900 transition-all duration-200"
                data-test="login-button">
                {{ __('Sign in') }}
            </flux:button>
        </form>
    </div>

    <!-- Sign Up Link -->
    @if (Route::has('register'))
        <div class="text-center">
            <p class="text-neutral-600 dark:text-neutral-400">
                {{ __('Don\'t have an account?') }}
                <flux:link
                    :href="route('register')"
                    wire:navigate
                    class="font-medium text-neutral-900 dark:text-white hover:text-neutral-700 dark:hover:text-neutral-300 transition-colors">
                    {{ __('Create one now') }}
                </flux:link>
            </p>
        </div>
    @endif
</div>
