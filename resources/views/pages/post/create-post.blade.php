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

                <!-- Loading Overlay -->
                <div id="loading-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                    <div class="bg-white dark:bg-zinc-800 rounded-2xl p-8 max-w-sm mx-4 text-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900 dark:border-white mx-auto mb-4"></div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Creating Post...</h3>
                        <p class="text-gray-600 dark:text-zinc-400 text-sm mb-4" id="loading-message">Please wait while we process your post</p>

                        <!-- Progress bar -->
                        <div class="w-full bg-gray-200 dark:bg-zinc-700 rounded-full h-2 mb-2">
                            <div id="progress-bar" class="bg-gray-900 dark:bg-white h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-zinc-500" id="progress-text">Preparing...</p>
                </div>
                </div>

                <!-- Form -->
                <form id="create-post-form" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
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
                            Add Media <span class="text-red-500">*</span> (Required)
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
                                    <p class="text-xs text-gray-400 dark:text-zinc-500 mt-1">Images or videos up to 10MB (Required)</p>
                            </div>
                            </label>
                        </div>

                        <!-- Preview Area -->
                        <div id="media-preview" class="mt-4 hidden">
                            <div class="relative">
                                <img id="preview-image" class="w-full max-h-96 object-contain rounded-xl" style="display: none;">
                                <video id="preview-video" class="w-full max-h-96 object-contain rounded-xl" controls style="display: none;"></video>
                                <button id="remove-media-btn" type="button" onclick="removeMedia()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors duration-200 hidden">
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

    <!-- Success Snackbar -->
    <div id="success-snackbar" class="hidden fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-y-full">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span id="success-message">Post created successfully!</span>
                    </div>
                </div>

    <!-- Error Snackbar -->
    <div id="error-snackbar" class="hidden fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-y-full">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span id="error-message">Failed to create post. Please try again.</span>
        </div>
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
            const removeBtn = document.getElementById('remove-media-btn');

            // Reset classes
            previewImage.className = 'w-full max-h-96 object-contain rounded-xl transition-all duration-300 ease-in-out shadow-lg';
            previewVideo.className = 'w-full max-h-96 object-contain rounded-xl transition-all duration-300 ease-in-out shadow-lg';

            preview.classList.remove('hidden');
            preview.classList.add('fade-in');

                    if (file.type.startsWith('image/')) {
                previewImage.style.display = 'block';
                previewVideo.style.display = 'none';
                removeBtn.classList.remove('hidden'); // Show delete button

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
                removeBtn.classList.remove('hidden'); // Show delete button

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
            const removeBtn = document.getElementById('remove-media-btn');

            fileInput.value = '';
            preview.classList.add('hidden');
            removeBtn.classList.add('hidden'); // Hide delete button

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

        // Regular Form submission with client-side validation
        document.getElementById('create-post-form').addEventListener('submit', function(e) {
            const content = textarea.value.trim();
            if (!content) {
                e.preventDefault();
                textarea.focus();
                textarea.classList.add('border-red-500', 'dark:border-red-400');
                showError('Please enter some content for your post.');
                return;
            }

            // Check if media is selected
            const mediaInput = document.getElementById('media');
            if (!mediaInput.files || mediaInput.files.length === 0) {
                e.preventDefault();
                showError('Please select an image or video to upload.');
                return;
            }

            // Show loading overlay for better UX
            showLoadingOverlay();

            // Let the form submit normally - no preventDefault()
        });

        // Loading overlay functions
        function showLoadingOverlay() {
            const overlay = document.getElementById('loading-overlay');
            const message = document.getElementById('loading-message');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');

            // Reset progress
            progressBar.style.width = '0%';
            progressText.textContent = 'Preparing...';

            // Check if there's a file being uploaded
            const fileInput = document.getElementById('media');
            if (fileInput.files.length > 0) {
                message.textContent = 'Uploading media and creating post...';
                simulateProgress();
            } else {
                message.textContent = 'Creating post...';
                progressBar.style.width = '100%';
                progressText.textContent = 'Processing...';
            }

            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function simulateProgress() {
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            let progress = 0;

            const interval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 90) progress = 90;

                progressBar.style.width = progress + '%';

                if (progress < 30) {
                    progressText.textContent = 'Uploading media...';
                } else if (progress < 70) {
                    progressText.textContent = 'Processing media...';
                } else {
                    progressText.textContent = 'Creating post...';
                }

                if (progress >= 90) {
                    clearInterval(interval);
                }
            }, 200);
        }

        function hideLoadingOverlay() {
            const overlay = document.getElementById('loading-overlay');
            overlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Form state management
        function disableForm() {
            const form = document.getElementById('create-post-form');
            const inputs = form.querySelectorAll('input, textarea, button');
            inputs.forEach(input => {
                input.disabled = true;
            });
        }

        function enableForm() {
            const form = document.getElementById('create-post-form');
            const inputs = form.querySelectorAll('input, textarea, button');
            inputs.forEach(input => {
                input.disabled = false;
            });
        }

        // Snackbar functions
        function showSuccess(message) {
            const snackbar = document.getElementById('success-snackbar');
            const messageEl = document.getElementById('success-message');

            messageEl.textContent = message;
            snackbar.classList.remove('hidden', 'translate-y-full');
            snackbar.classList.add('translate-y-0');

            // Auto hide after 3 seconds
            setTimeout(() => {
                hideSnackbar(snackbar);
            }, 3000);
        }

        function showError(message) {
            const snackbar = document.getElementById('error-snackbar');
            const messageEl = document.getElementById('error-message');

            messageEl.textContent = message;
            snackbar.classList.remove('hidden', 'translate-y-full');
            snackbar.classList.add('translate-y-0');

            // Auto hide after 5 seconds
            setTimeout(() => {
                hideSnackbar(snackbar);
            }, 5000);
        }

        function hideSnackbar(snackbar) {
            snackbar.classList.remove('translate-y-0');
            snackbar.classList.add('translate-y-full');

            setTimeout(() => {
                snackbar.classList.add('hidden');
            }, 300);
        }

        // Auto-resize textarea
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    </script>
</x-layouts.app>
