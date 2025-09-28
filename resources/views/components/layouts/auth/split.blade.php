<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased dark:bg-neutral-950">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <!-- Left Side - Branding & Visual -->
            <div class="relative hidden h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-neutral-800">
                <!-- Background Image -->
                <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                     style="background-image: url('{{ asset('assets/images/bg-login-threadloop-hitam.png') }}');">
                    <div class="absolute inset-0 bg-black/40"></div>
                </div>

                <!-- Logo and Brand -->
                <div class="relative z-20 flex items-center text-lg font-medium mb-8">
                    <img src="{{ asset('assets/images/logo-ThreadLoop2-warna-putih.svg') }}"
                         alt="ThreadLoop"
                         class="h-10 w-auto me-3">
                </div>

                <!-- Brand Message -->
                <div class="relative z-20 mt-auto space-y-6">
                    <div class="space-y-4">
                        <h2 class="text-3xl font-bold leading-tight">
                            Connect. Share.
                            <span class="text-neutral-300">Inspire.</span>
                        </h2>
                        <p class="text-lg text-neutral-300 leading-relaxed">
                            Join the community where stories come alive. Share your moments,
                            discover new perspectives, and connect with people who matter.
                        </p>
                    </div>

                    <!-- Features -->
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span class="text-neutral-200">Share your stories with the world</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span class="text-neutral-200">Discover amazing content</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span class="text-neutral-200">Connect with like-minded people</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Auth Form -->
            <div class="w-full lg:p-8 bg-white dark:bg-neutral-900">
                <div class="mx-auto my-10 flex w-full flex-col justify-center space-y-6 sm:w-[400px]">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
