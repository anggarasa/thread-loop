<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Notifications\FollowedUserPosted;

class PostController extends Controller
{
    public function create()
    {
        return view('pages.post.create-post');
    }

    public function store(Request $request)
    {
        try {
            // Debug: Log request data
            Log::info('Post creation request data:', [
                'content' => $request->input('content'),
                'has_media' => $request->hasFile('media'),
                'all_input' => $request->all()
            ]);

            $request->validate([
                'content' => 'required|string|max:500',
                'media' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240', // 10MB max
            ]);

            $postData = [
                'user_id' => Auth::id(),
                'content' => $request->content,
                'likes_count' => 0,
                'comments_count' => 0,
            ];

            // Handle media upload to Cloudinary if present
            if ($request->hasFile('media')) {
                $file = $request->file('media');
                $mediaType = $file->getMimeType();

                // Determine media type
                if (str_starts_with($mediaType, 'image/')) {
                    $postData['media_type'] = 'image';
                } elseif (str_starts_with($mediaType, 'video/')) {
                    $postData['media_type'] = 'video';
                }

                // Upload to Cloudinary
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
            }

            // Create post in database
            $post = Post::create($postData);

            // Notify followers that this user posted something new
            /** @var User $author */
            $author = Auth::user();
            $followerIds = $author->followers()->pluck('follower_id');
            if ($followerIds->isNotEmpty()) {
                $followers = User::whereIn('id', $followerIds)->get();
                foreach ($followers as $follower) {
                    $follower->notify(new FollowedUserPosted($author, $post));
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully!',
                'redirect_url' => route('homePage')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Post creation validation failed:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed. Please check your input.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Post creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create post. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
