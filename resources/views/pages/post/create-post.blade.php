<x-layouts.app>
    <div class="min-h-screen bg-white dark:bg-zinc-900">
        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border border-gray-200 dark:border-zinc-700 overflow-hidden"></div>
                <!-- Header -->
                <div class="px-6 py-6 border-b border-gray-100 dark:border-zinc-700">
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Create New Post</h1>
                    <p class="text-gray-600 dark:text-zinc-400 mt-1">Share your thoughts with the community</p>
                </div>

                <!-- Form -->
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf

                    <!-- Content Input -->
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-3">
                            What's on your mind?
                        </label>
                        <div class="relative">
                            <textarea
                                id="content"
                                name="content"
                                rows="6"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-zinc-600 rounded-xl focus:ring-2 focus:ring-gray-900 dark:focus:ring-zinc-400 focus:border-transparent resize-none transition-all duration-200 placeholder-gray-400 dark:placeholder-zinc-500 text-gray-900 dark:text-white bg-white dark:bg-zinc-700"
                                placeholder="Share your thoughts, ideas, or experiences..."
                                maxlength="500"
                                required
                            >{{ old('content') }}</textarea>

                            <!-- Character Counter -->
                            <div class="absolute bottom-3 right-3 text-xs text-gray-400 dark:text-zinc-500">
                                <span id="char-count">0</span>/500
                            </div>
                        </div>

                        @error('content')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Media Upload Section -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-zinc-300 mb-3">
                            Add Media (Optional)
                        </label>

                        <!-- File Input -->
                        <div class="relative">
                            <input
                                type="file"
                                id="media"
                                name="media"
                                accept="image/*,video/*"
                                class="hidden"
                                onchange="handleFileSelect(event)"
                            >

                            <!-- Upload Button -->
                            <label for="media" class="flex items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-zinc-600 rounded-xl cursor-pointer hover:border-gray-400 dark:hover:border-zinc-500 hover:bg-gray-50 dark:hover:bg-zinc-700 transition-all duration-200 group bg-white dark:bg-zinc-800">
                                <div class="text-center">
                                    <svg class="mx-auto h-8 w-8 text-gray-400 dark:text-zinc-500 group-hover:text-gray-500 dark:group-hover:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500 dark:text-zinc-400 group-hover:text-gray-600 dark:group-hover:text-zinc-300 mt-2">
                                        <span class="font-medium">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-zinc-500 mt-1">Images or videos up to 10MB</p>
                                </div>
                            </label>
                        </div>

                        <!-- Preview Area -->
                        <div id="media-preview" class="mt-4 hidden">
                            <div class="relative">
                                <img id="preview-image" class="w-full max-h-96 object-contain rounded-xl" style="display: none;">
                                <video id="preview-video" class="w-full max-h-96 object-contain rounded-xl" controls style="display: none;"></video>
                                <button type="button" onclick="removeMedia()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        @error('media')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
                        <a href="{{ route('homePage') }}"
                           class="px-6 py-3 text-gray-700 dark:text-zinc-300 bg-gray-100 dark:bg-zinc-700 hover:bg-gray-200 dark:hover:bg-zinc-600 rounded-xl font-medium transition-colors duration-200 text-center">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-gray-900 dark:bg-zinc-100 hover:bg-gray-800 dark:hover:bg-zinc-200 text-white dark:text-zinc-900 rounded-xl font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Create Post
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- JavaScript for enhanced UX -->
    <script>
        // Character counter
        const textarea = document.getElementById('content');
        const charCount = document.getElementById('char-count');

        textarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;

            if (count > 450) {
                charCount.classList.add('text-red-500', 'dark:text-red-400');
                charCount.classList.remove('text-gray-400', 'dark:text-zinc-500');
            } else {
                charCount.classList.remove('text-red-500', 'dark:text-red-400');
                charCount.classList.add('text-gray-400', 'dark:text-zinc-500');
            }
        });

        // File handling
        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            const preview = document.getElementById('media-preview');
            const previewImage = document.getElementById('preview-image');
            const previewVideo = document.getElementById('preview-video');

            // Reset classes
            previewImage.className = 'w-full max-h-96 object-contain rounded-xl transition-all duration-300 ease-in-out shadow-lg';
            previewVideo.className = 'w-full max-h-96 object-contain rounded-xl transition-all duration-300 ease-in-out shadow-lg';

            preview.classList.remove('hidden');
            preview.classList.add('fade-in');

            if (file.type.startsWith('image/')) {
                previewImage.style.display = 'block';
                previewVideo.style.display = 'none';

                // Create image to get natural dimensions
                const img = new Image();
                img.onload = function() {
                    const aspectRatio = this.naturalWidth / this.naturalHeight;

                    // Remove existing aspect ratio classes
                    previewImage.classList.remove('portrait-preview', 'landscape-preview', 'square-preview');

                    // Add appropriate class based on aspect ratio
                    if (aspectRatio > 1.2) {
                        // Landscape
                        previewImage.classList.add('landscape-preview');
                    } else if (aspectRatio < 0.8) {
                        // Portrait
                        previewImage.classList.add('portrait-preview');
                    } else {
                        // Square or near square
                        previewImage.classList.add('square-preview');
                    }

                    // Ensure it doesn't exceed container width
                    previewImage.style.maxWidth = '100%';
                    previewImage.style.width = 'auto';
                    previewImage.style.height = 'auto';
                };
                img.src = URL.createObjectURL(file);
                previewImage.src = URL.createObjectURL(file);

            } else if (file.type.startsWith('video/')) {
                previewVideo.style.display = 'block';
                previewImage.style.display = 'none';

                // Create video element to get dimensions
                const video = document.createElement('video');
                video.onloadedmetadata = function() {
                    const aspectRatio = this.videoWidth / this.videoHeight;

                    // Remove existing aspect ratio classes
                    previewVideo.classList.remove('portrait-preview', 'landscape-preview', 'square-preview');

                    // Add appropriate class based on aspect ratio
                    if (aspectRatio > 1.2) {
                        // Landscape
                        previewVideo.classList.add('landscape-preview');
                    } else if (aspectRatio < 0.8) {
                        // Portrait
                        previewVideo.classList.add('portrait-preview');
                    } else {
                        // Square or near square
                        previewVideo.classList.add('square-preview');
                    }

                    // Ensure it doesn't exceed container width
                    previewVideo.style.maxWidth = '100%';
                    previewVideo.style.width = 'auto';
                    previewVideo.style.height = 'auto';
                };
                video.src = URL.createObjectURL(file);
                previewVideo.src = URL.createObjectURL(file);
            }
        }

        function removeMedia() {
            const fileInput = document.getElementById('media');
            const preview = document.getElementById('media-preview');
            const previewImage = document.getElementById('preview-image');
            const previewVideo = document.getElementById('preview-video');

            fileInput.value = '';
            preview.classList.add('hidden');

            // Reset image
            previewImage.style.display = 'none';
            previewImage.src = '';
            previewImage.className = 'w-full max-h-96 object-contain rounded-xl transition-all duration-300 ease-in-out shadow-lg';
            previewImage.classList.remove('portrait-preview', 'landscape-preview', 'square-preview');

            // Reset video
            previewVideo.style.display = 'none';
            previewVideo.src = '';
            previewVideo.className = 'w-full max-h-96 object-contain rounded-xl transition-all duration-300 ease-in-out shadow-lg';
            previewVideo.classList.remove('portrait-preview', 'landscape-preview', 'square-preview');
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const content = textarea.value.trim();
            if (!content) {
                e.preventDefault();
                textarea.focus();
                textarea.classList.add('border-red-500', 'dark:border-red-400');
                return;
            }
        });

        // Auto-resize textarea
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    </script>
</x-layouts.app>
