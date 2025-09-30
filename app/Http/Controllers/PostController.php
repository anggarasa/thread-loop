<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function create()
    {
        return view('pages.post.create-post');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'media' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240', // 10MB max
        ]);

        $postData = [
            'user_id' => Auth::id(),
            'content' => $request->content,
            'likes_count' => 0,
            'comments_count' => 0,
        ];

        // Handle media upload if present
        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $mediaType = $file->getMimeType();

            if (str_starts_with($mediaType, 'image/')) {
                $postData['media_type'] = 'image';
            } elseif (str_starts_with($mediaType, 'video/')) {
                $postData['media_type'] = 'video';
            }

            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('posts', $filename, 'public');
            $postData['media_path'] = $path;
            $postData['media_url'] = Storage::url($path);
        }

        Post::create($postData);

        return redirect()->route('homePage')->with('success', 'Post created successfully!');
    }
}
