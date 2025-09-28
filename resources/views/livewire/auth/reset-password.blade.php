<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="text-center space-y-2">
        <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">
            {{ __('Reset your password') }}
        </h1>
        <p class="text-neutral-600 dark:text-neutral-400">
            {{ __('Enter your new password below to complete the reset') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <!-- Reset Password Form -->
    <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-8 shadow-sm">
        <form method="POST" wire:submit="resetPassword" class="flex flex-col gap-6">
            <!-- Email Address -->
            <div class="space-y-2">
                <flux:input
                    wire:model="email"
                    :label="__('Email')"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="Enter your email"
                    class="h-12"
                />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <flux:input
                    wire:model="password"
                    :label="__('New Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="Enter your new password"
                    viewable
                    class="h-12"
                />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <flux:input
                    wire:model="password_confirmation"
                    :label="__('Confirm Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm your new password"
                    viewable
                    class="h-12"
                />
            </div>

            <!-- Submit Button -->
            <flux:button
                type="submit"
                variant="primary"
                class="w-full h-12 text-base font-medium rounded-xl bg-neutral-900 hover:bg-neutral-800 dark:bg-white dark:hover:bg-neutral-100 dark:text-neutral-900 transition-all duration-200">
                {{ __('Reset password') }}
            </flux:button>
        </form>
    </div>

    <!-- Back to Login -->
    <div class="text-center">
        <p class="text-neutral-600 dark:text-neutral-400">
            {{ __('Remember your password?') }}
            <flux:link
                :href="route('login')"
                wire:navigate
                class="font-medium text-neutral-900 dark:text-white hover:text-neutral-700 dark:hover:text-neutral-300 transition-colors">
                {{ __('Back to sign in') }}
            </flux:link>
        </p>
    </div>
</div>
