<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <!-- Profile Image Section -->
        <div class="mb-8">
            <div class="flex items-center space-x-6">
                <div class="relative">
                    @if(auth()->user()->profile_url)
                        <img src="{{ auth()->user()->profile_url }}" alt="Profile Image"
                             class="w-24 h-24 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute -bottom-2 -right-2 flex space-x-1">
                        <button type="button"
                                onclick="openProfileImageModal()"
                                class="bg-blue-500 hover:bg-blue-600 text-white rounded-full p-2 shadow-lg transition-colors duration-200"
                                title="Change profile picture">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </button>
                        @if(auth()->user()->profile_url)
                        <button type="button"
                                onclick="deleteProfileImage()"
                                class="bg-red-500 hover:bg-red-600 text-white rounded-full p-2 shadow-lg transition-colors duration-200"
                                title="Delete profile picture">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Click the edit icon to change your profile picture</p>
                </div>
            </div>
        </div>

        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">

            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <flux:input wire:model="username" :label="__('Username')" type="text" required autofocus autocomplete="username" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            @if (session('error'))
                <flux:text class="text-red-600 text-sm">{{ session('error') }}</flux:text>
            @endif

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>

    <!-- Profile Image Upload Modal -->
    <div id="profileImageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Update Profile Picture</h3>
                    <button onclick="closeProfileImageModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Drag & Drop Area -->
                <div id="dropArea" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors duration-200">
                    <div id="dropContent">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Click to upload</span> or drag and drop
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                    </div>
                    <input type="file" id="profileImageInput" accept="image/*" class="hidden">
                </div>

                <!-- Image Preview -->
                <div id="imagePreview" class="mt-4 hidden">
                    <img id="previewImg" class="mx-auto h-32 w-32 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                </div>

                <!-- Error Message -->
                <div id="errorMessage" class="mt-4 text-red-600 text-sm hidden"></div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button onclick="closeProfileImageModal()"
                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md transition-colors duration-200">
                        Cancel
                    </button>
                    <button id="saveProfileImage" onclick="uploadProfileImage()"
                            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedFile = null;

        function openProfileImageModal() {
            document.getElementById('profileImageModal').classList.remove('hidden');
            resetModal();
        }

        function closeProfileImageModal() {
            document.getElementById('profileImageModal').classList.add('hidden');
            resetModal();
        }

        function resetModal() {
            selectedFile = null;
            document.getElementById('dropContent').classList.remove('hidden');
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
            document.getElementById('saveProfileImage').disabled = true;
            document.getElementById('profileImageInput').value = '';
        }

        // Drag and drop functionality
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('profileImageInput');

        dropArea.addEventListener('click', () => fileInput.click());

        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900');
        });

        dropArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropArea.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900');
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFileSelect(files[0]);
            }
        });

        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });

        function handleFileSelect(file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                showError('Please select a valid image file.');
                return;
            }

            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                showError('File size must be less than 5MB.');
                return;
            }

            selectedFile = file;

            // Show preview
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('dropContent').classList.add('hidden');
                document.getElementById('imagePreview').classList.remove('hidden');
                document.getElementById('saveProfileImage').disabled = false;
                document.getElementById('errorMessage').classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }

        function showError(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorMessage').classList.remove('hidden');
            document.getElementById('saveProfileImage').disabled = true;
        }

        function uploadProfileImage() {
            if (!selectedFile) return;

            const formData = new FormData();
            formData.append('profile_image', selectedFile);

            // Show loading state
            const saveBtn = document.getElementById('saveProfileImage');
            const originalText = saveBtn.textContent;
            saveBtn.textContent = 'Uploading...';
            saveBtn.disabled = true;

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                showError('CSRF token not found. Please refresh the page and try again.');
                return;
            }

            fetch('{{ route("profile.upload-image") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update the profile image in the UI
                    const profileImg = document.querySelector('img[alt="Profile Image"]');
                    if (profileImg) {
                        profileImg.src = data.profile_url;
                    } else {
                        // If no profile image exists, reload the page to show the new image
                        window.location.reload();
                    }
                    closeProfileImageModal();
                } else {
                    showError(data.message || 'Failed to upload image');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('An error occurred while uploading the image. Please try again.');
            })
            .finally(() => {
                saveBtn.textContent = originalText;
                saveBtn.disabled = false;
            });
        }

        // Close modal when clicking outside
        document.getElementById('profileImageModal').addEventListener('click', (e) => {
            if (e.target.id === 'profileImageModal') {
                closeProfileImageModal();
            }
        });

        // Delete profile image function
        function deleteProfileImage() {
            if (!confirm('Are you sure you want to delete your profile picture?')) {
                return;
            }

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                alert('CSRF token not found. Please refresh the page and try again.');
                return;
            }

            fetch('{{ route("profile.delete-image") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Reload the page to show the default avatar
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to delete profile image');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the profile image. Please try again.');
            });
        }
    </script>
</section>

