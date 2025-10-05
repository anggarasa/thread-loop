<?php

namespace App\Livewire\Guest;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.guest')]
class GuestHomePage extends Component
{
    public $posts = [];
    public $page = 1;
    public $hasMorePosts = true;
    public $loading = false;

    public function mount()
    {
        $this->loadPosts();
    }

    public function loadPosts()
    {
        if ($this->loading || !$this->hasMorePosts) {
            return;
        }

        $this->loading = true;

        $newPosts = Post::with('user')
            ->latest()
            ->skip(($this->page - 1) * 10)
            ->limit(10)
            ->get();

        if ($newPosts->count() < 10) {
            $this->hasMorePosts = false;
        }

        if ($this->page === 1) {
            $this->posts = $newPosts;
        } else {
            $this->posts = $this->posts->concat($newPosts);
        }

        $this->page++;
        $this->loading = false;
    }

    public function loadMore()
    {
        $this->loadPosts();
    }

    public function render()
    {
        return view('livewire.guest.guest-home-page');
    }
}
