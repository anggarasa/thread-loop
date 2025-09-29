<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'media_type',
        'media_path',
        'media_url',
        'likes_count',
        'comments_count',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isTextPost(): bool
    {
        return is_null($this->media_type);
    }

    public function isImagePost(): bool
    {
        return $this->media_type === 'image';
    }

    public function isVideoPost(): bool
    {
        return $this->media_type === 'video';
    }
}
