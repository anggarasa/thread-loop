<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="text-center space-y-2">
        <h1 class="text-3xl font-bold text-neutral-900 dark:text-white">
            {{ __('Join ThreadLoop') }}
        </h1>
        <p class="text-neutral-600 dark:text-neutral-400">
            {{ __('Create your account and start sharing your stories') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <!-- Register Form -->
    <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-8 shadow-sm">
        <form method="POST" wire:submit="register" class="flex flex-col gap-6">
            <!-- Name -->
            <div class="space-y-2">
                <flux:input
                    wire:model="name"
                    :label="__('Full name')"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Enter your full name"
                    class="h-12"
                />
            </div>

            <!-- Email Address -->
            <div class="space-y-2">
                <flux:input
                    wire:model="email"
                    :label="__('Email address')"
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
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="Create a strong password"
                    viewable
                    class="h-12"
                />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <flux:input
                    wire:model="password_confirmation"
                    :label="__('Confirm password')"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm your password"
                    viewable
                    class="h-12"
                />
            </div>

            <!-- Submit Button -->
            <flux:button
                type="submit"
                variant="primary"
                class="w-full h-12 text-base font-medium rounded-xl bg-neutral-900 hover:bg-neutral-800 dark:bg-white dark:hover:bg-neutral-100 dark:text-neutral-900 transition-all duration-200">
                {{ __('Create account') }}
            </flux:button>
        </form>
    </div>

    <!-- Sign In Link -->
    <div class="text-center">
        <p class="text-neutral-600 dark:text-neutral-400">
            {{ __('Already have an account?') }}
            <flux:link
                :href="route('login')"
                wire:navigate
                class="font-medium text-neutral-900 dark:text-white hover:text-neutral-700 dark:hover:text-neutral-300 transition-colors">
                {{ __('Sign in instead') }}
            </flux:link>
        </p>
    </div>
</div>
