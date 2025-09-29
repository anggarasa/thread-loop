<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;

    #[Validate('nullable|string|max:2000')]
    public $content = '';

    #[Validate('nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240')]
    public $image;

    #[Validate('nullable|file|mimes:mp4,avi,mov,wmv,webm|max:102400')]
    public $video;

    public $mediaType = null; // 'image', 'video', or null for text only

    public function rules()
    {
        return [
            'content' => 'nullable|string|max:2000',
            'image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'video' => 'nullable|file|mimes:mp4,avi,mov,wmv,webm|max:102400',
        ];
    }

    public function updatedImage()
    {
        $this->video = null;
        $this->mediaType = 'image';
        $this->validate(['image']);
    }

    public function updatedVideo()
    {
        $this->image = null;
        $this->mediaType = 'video';
        $this->validate(['video']);
    }

    public function removeImage()
    {
        $this->image = null;
        $this->mediaType = null;
    }

    public function removeVideo()
    {
        $this->video = null;
        $this->mediaType = null;
    }

    public function createPost()
    {
        $this->validate();

        // Check if at least content or media is provided
        if (empty($this->content) && !$this->image && !$this->video) {
            $this->addError('content', 'Post harus memiliki konten atau media.');
            return;
        }

        $postData = [
            'user_id' => Auth::id(),
            'content' => $this->content,
        ];

        // Handle media upload
        if ($this->image) {
            $mediaPath = $this->image->store('posts/images', 'public');
            $postData['media_type'] = 'image';
            $postData['media_path'] = $mediaPath;
            $postData['media_url'] = Storage::url($mediaPath);
        } elseif ($this->video) {
            $mediaPath = $this->video->store('posts/videos', 'public');
            $postData['media_type'] = 'video';
            $postData['media_path'] = $mediaPath;
            $postData['media_url'] = Storage::url($mediaPath);
        }

        Post::create($postData);

        // Reset form
        $this->reset(['content', 'image', 'video', 'mediaType']);

        // Emit event to refresh posts list
        $this->dispatch('post-created');

        session()->flash('message', 'Post berhasil dibuat!');
    }

    public function render()
    {
        return view('livewire.post.create-post');
    }
}
