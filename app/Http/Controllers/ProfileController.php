<?php

namespace App\Http\Controllers;

use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Delete old profile image from Cloudinary
     */
    private function deleteOldProfileImage($profileUrl)
    {
        try {
            if (empty($profileUrl)) {
                return true;
            }

            // Extract public_id from Cloudinary URL
            // URL format: https://res.cloudinary.com/cloud_name/image/upload/v1234567890/folder/public_id.ext
            $parsedUrl = parse_url($profileUrl);
            $path = $parsedUrl['path'] ?? '';

            // Extract public_id from path
            if (preg_match('/\/upload\/v\d+\/(.+)$/', $path, $matches)) {
                $publicId = $matches[1];
                // Remove file extension
                $publicId = preg_replace('/\.[^.]+$/', '', $publicId);

                // Delete from Cloudinary
                Cloudinary::adminApi()->deleteAssets([$publicId]);

                Log::info('Old profile image deleted from Cloudinary: ' . $publicId);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::warning('Failed to delete old profile image: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete profile image from Cloudinary (public method for external use)
     */
    public function deleteProfileImage()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $profileUrl = $user->profile_url;

            if (empty($profileUrl)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No profile image to delete'
                ], 400);
            }

            // Delete from Cloudinary
            $deleted = $this->deleteOldProfileImage($profileUrl);

            if ($deleted) {
                // Clear profile URL from database
                /** @var \App\Models\User $user */
                $user->profile_url = null;
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Profile image deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete profile image from Cloudinary'
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Failed to delete profile image: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete profile image. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload profile image to Cloudinary
     */
    public function uploadProfileImage(Request $request)
    {
        try {
            // Validate the uploaded file
            $validator = Validator::make($request->all(), [
                'profile_image' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:4096'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Get the uploaded file
            $file = $request->file('profile_image');

            // Delete old profile image from Cloudinary if exists
            $oldProfileUrl = $user->profile_url;
            if (!empty($oldProfileUrl)) {
                $this->deleteOldProfileImage($oldProfileUrl);
            }

            // Upload to Cloudinary
            $uploadResult = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                'folder' => 'profile-images',
                'public_id' => uniqid(),
                'resource_type' => 'auto',
                'transformation' => [
                    'width' => 500,
                    'height' => 500,
                    'crop' => 'fill',
                ]
            ]);

            // Update user profile URL
            /** @var \App\Models\User $user */
            $user->profile_url = $uploadResult['secure_url'];
            $user->save();

            Log::info('Profile image uploaded successfully: ' . $user->profile_url);

            return response()->json([
                'success' => true,
                'message' => 'Profile image uploaded successfully',
                'profile_url' => $user->profile_url
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to upload profile image: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload profile image. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
