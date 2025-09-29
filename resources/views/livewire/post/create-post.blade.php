<div class="bg-white dark:bg-neutral-800 rounded-xl shadow-sm border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
    <div class="flex items-start space-x-4">
        <!-- User Avatar -->
        <div class="flex-shrink-0">
            @if(auth()->user()->profile_url)
                <img
                    src="{{ auth()->user()->profile_url }}"
                    alt="Profile Picture"
                    class="w-10 h-10 rounded-full object-cover"
                >
            @else
                <div class="w-10 h-10 bg-neutral-700 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                    {{ auth()->user()->initials() }}
                </div>
            @endif
        </div>

        <!-- Post Form -->
        <div class="flex-1 min-w-0">
            <form wire:submit.prevent="createPost" class="space-y-4">
                <!-- Content Textarea -->
                <div>
                    <textarea
                        wire:model.live="content"
                        placeholder="Apa yang sedang Anda pikirkan?"
                        class="w-full px-4 py-3 border border-neutral-300 dark:border-neutral-600 rounded-xl resize-none focus:ring-2 focus:ring-neutral-500 focus:border-transparent dark:bg-neutral-700 dark:text-white placeholder-neutral-500 dark:placeholder-neutral-400 transition-all duration-200"
                        rows="3"
                        maxlength="2000"
                    ></textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Media Preview -->
                @if($image)
                    <div class="relative group">
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full max-h-64 object-cover rounded-xl">
                        <button
                            type="button"
                            wire:click="removeImage"
                            class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                @if($video)
                    <div class="relative group">
                        <video src="{{ $video->temporaryUrl() }}" controls class="w-full max-h-64 object-cover rounded-xl">
                            Your browser does not support the video tag.
                        </video>
                        <button
                            type="button"
                            wire:click="removeVideo"
                            class="absolute top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                <!-- Media Upload Buttons -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Image Upload -->
                        <label class="flex items-center space-x-2 cursor-pointer text-neutral-600 dark:text-neutral-400 hover:text-neutral-500 dark:hover:text-neutral-400 transition-colors duration-200">
                            <input type="file" wire:model="image" accept="image/*" class="hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-medium">Foto</span>
                        </label>

                        <!-- Video Upload -->
                        <label class="flex items-center space-x-2 cursor-pointer text-neutral-600 dark:text-neutral-400 hover:text-neutral-500 dark:hover:text-neutral-400 transition-colors duration-200">
                            <input type="file" wire:model="video" accept="video/*" class="hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-medium">Video</span>
                        </label>
                    </div>

                    <!-- Character Count -->
                    <div class="text-sm text-neutral-500 dark:text-neutral-400">
                        {{ strlen($content) }}/2000
                    </div>
                </div>

                <!-- Error Messages -->
                @error('image')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                @error('video')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <flux:button type="submit" variant="primary">
                        {{ __('Posting') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="text-sm text-green-800 dark:text-green-200">{{ session('message') }}</p>
            </div>
        </div>
    @endif
</div>
