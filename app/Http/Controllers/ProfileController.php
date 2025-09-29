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
     * Upload profile image to Cloudinary
     */
    public function uploadProfileImage(Request $request)
    {
        try {
            // Validate the uploaded file
            $validator = Validator::make($request->all(), [
                'profile_image' => ['required', 'image', 'mimes:png,jpg,jpeg', 'max:5024'],
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
