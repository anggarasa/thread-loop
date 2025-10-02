<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    /**
     * Display a shared post for public viewing
     */
    public function show(Post $post)
    {
        // Load the post with its user relationship
        $post->load('user');

        // Check if post exists and has a user
        if (!$post->user) {
            abort(404, 'Post not found or user not available');
        }

        return view('pages.share.post-share', compact('post'));
    }
}
