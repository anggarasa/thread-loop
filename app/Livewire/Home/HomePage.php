<?php

namespace App\Livewire\Home;

use App\Models\Post;
use App\Models\User;
use Livewire\Component;

class HomePage extends Component
{
    public function render()
    {
        // Fetch posts with user relationship, ordered by latest first
        $posts = Post::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Fetch suggested users (excluding current user)
        $suggestedUsers = User::where('id', '!=', auth()->id())
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('livewire.home.home-page', [
            'posts' => $posts,
            'suggestedUsers' => $suggestedUsers,
        ]);
    }
}
