<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class PostDetail extends Component
{
    use WithPagination;

    public Post $post;
    public $newComment = '';
    public $showComments = false;
    public $isLiked = false;
    public $likesCount = 0;

    public function mount(Post $post)
    {
        $this->post = $post->load(['user', 'comments.user']);
        $this->likesCount = $this->post->likes_count;
        $this->isLiked = $this->post->isLikedBy(auth()->user());
    }

    public function toggleLike()
    {
        if ($this->isLiked) {
            $this->post->unlike(auth()->user());
            $this->likesCount--;
        } else {
            $this->post->like(auth()->user());
            $this->likesCount++;
        }
        $this->isLiked = !$this->isLiked;
    }

    public function addComment()
    {
        $this->validate([
            'newComment' => 'required|string|max:500',
        ]);

        $this->post->comments()->create([
            'user_id' => auth()->id(),
            'content' => $this->newComment,
        ]);

        $this->post->increment('comments_count');
        $this->newComment = '';
        $this->showComments = true;
    }

    public function toggleComments()
    {
        $this->showComments = !$this->showComments;
    }

    public function goBack()
    {
        return redirect()->back();
    }

    public function render()
    {
        $comments = $this->post->comments()
            ->with('user')
            ->latest()
            ->paginate(10);

        $suggestedPosts = Post::with('user')
            ->where('id', '!=', $this->post->id)
            ->where('user_id', '!=', $this->post->user_id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('livewire.posts.post-detail', [
            'comments' => $comments,
            'suggestedPosts' => $suggestedPosts,
        ]);
    }
}
