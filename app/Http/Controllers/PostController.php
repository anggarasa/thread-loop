<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Notifications\FollowedUserPosted;
use Exception;

class PostController extends Controller
{
    public function create()
    {
        return view('pages.post.create-post');
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        $rateLimitKey = "create-post:{$userId}";

        // Rate limiting: 5 posts per minute
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return response()->json([
                'success' => false,
                'message' => "Too many posts created. Please wait {$seconds} seconds before creating another post.",
            ], 429);
        }

        try {
            // Enhanced validation
            $request->validate([
                'content' => 'required|string|max:500|min:1',
                'media' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240', // Made required
            ], [
                'content.required' => 'Post content is required.',
                'content.max' => 'Post content cannot exceed 500 characters.',
                'content.min' => 'Post content must be at least 1 character.',
                'media.required' => 'Media upload is required.',
                'media.mimes' => 'Only JPEG, PNG, JPG, GIF, MP4, MOV, and AVI files are allowed.',
                'media.max' => 'File size cannot exceed 10MB.',
            ]);

            // Media is now required, so no need for this check

            DB::beginTransaction();

            $postData = [
                'user_id' => $userId,
                'content' => $request->content,
                'likes_count' => 0,
                'comments_count' => 0,
            ];

            // Handle media upload to Cloudinary (now required)
            $file = $request->file('media');
            $mediaType = $file->getMimeType();

            // Enhanced media type validation
            if (str_starts_with($mediaType, 'image/')) {
                $postData['media_type'] = 'image';
            } elseif (str_starts_with($mediaType, 'video/')) {
                $postData['media_type'] = 'video';
            } else {
                throw new Exception('Unsupported media type.');
            }

            // Upload to Cloudinary with error handling
            try {
                $uploadResult = Cloudinary::uploadApi()->upload($file->getRealPath(), [
                    'folder' => 'thread-loop/posts',
                    'resource_type' => $postData['media_type'],
                    'transformation' => [
                        'quality' => 'auto',
                        'fetch_format' => 'auto'
                    ]
                ]);

                $postData['media_path'] = $uploadResult['public_id'];
                $postData['media_url'] = $uploadResult['secure_url'];
            } catch (Exception $e) {
                Log::error('Cloudinary upload failed:', [
                    'user_id' => $userId,
                    'error' => $e->getMessage(),
                    'file_size' => $file->getSize(),
                    'file_type' => $mediaType
                ]);

                throw new Exception('Failed to upload media. Please try again.');
            }

            // Create post in database
            $post = Post::create($postData);

            // Notify followers efficiently using chunking
            $author = Auth::user();
            $followerIds = $author->followers()->pluck('follower_id');

            if ($followerIds->isNotEmpty()) {
                // Use chunking to avoid memory issues with large follower lists
                User::whereIn('id', $followerIds)
                    ->chunk(100, function ($followers) use ($author, $post) {
                        foreach ($followers as $follower) {
                            $follower->notify(new FollowedUserPosted($author, $post));
                        }
                    });
            }

            DB::commit();

            // Record successful post creation for rate limiting
            RateLimiter::hit($rateLimitKey, 60); // 1 minute decay

            // Create success message with View Post link
            $viewPostUrl = route('posts.show', $post);
            $successMessage = 'Post created successfully! <a href="' . $viewPostUrl . '" class="underline font-semibold hover:text-green-800 dark:hover:text-green-200 transition-colors">View Post</a>';

            // Check if this is an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'redirect_url' => route('homePage')
                ]);
            }

            // Regular form submission - redirect with success message
            return redirect()->route('homePage')
                ->with('success', $successMessage);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            Log::warning('Post creation validation failed:', [
                'user_id' => $userId,
                'errors' => $e->errors(),
                'ip' => $request->ip()
            ]);

            // Check if this is an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed. Please check your input.',
                    'errors' => $e->errors()
                ], 422);
            }

            // Regular form submission - redirect back with errors
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Post creation failed:', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip()
            ]);

            // Check if this is an AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create post. Please try again.',
                ], 500);
            }

            // Regular form submission - redirect back with error
            return redirect()->back()
                ->with('error', 'Failed to create post. Please try again.')
                ->withInput();
        }
    }

    public function destroy(Request $request, Post $post)
    {
        // Check if user is authorized to delete this post
        if (!$post->canBeDeletedBy(Auth::user())) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to delete this post.',
                ], 403);
            }
            return redirect()->back()->with('error', 'You are not authorized to delete this post.');
        }

        try {
            DB::beginTransaction();

            // Delete media from Cloudinary if it exists
            if ($post->media_path) {
                try {
                    Cloudinary::uploadApi()->destroy($post->media_path);
                } catch (Exception $e) {
                    Log::warning('Failed to delete media from Cloudinary:', [
                        'post_id' => $post->id,
                        'media_path' => $post->media_path,
                        'error' => $e->getMessage()
                    ]);
                    // Continue with post deletion even if media deletion fails
                }
            }

            // Delete the post (this will cascade delete related records due to foreign key constraints)
            $post->delete();

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Post deleted successfully.',
                ]);
            }

            return redirect()->route('homePage')
                ->with('success', 'Post deleted successfully.');

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Post deletion failed:', [
                'post_id' => $post->id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete post. Please try again.',
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to delete post. Please try again.');
        }
    }
}
