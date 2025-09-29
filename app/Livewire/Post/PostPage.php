<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PostPage extends Component
{
    use WithPagination;

    protected $listeners = ['post-created' => '$refresh'];

    public function render()
    {
        $posts = Post::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.post.post-page', compact('posts'));
    }
}
