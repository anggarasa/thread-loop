<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'media_type',
        'media_path',
        'media_url',
        'likes_count',
        'comments_count',
    ];

    protected static function boot()
    {
        parent::boot();

        // Update likes_count when likes are attached/detached
        static::updating(function ($post) {
            if ($post->isDirty('likes_count')) {
                // Prevent infinite loops by not updating if likes_count is already being updated
                return;
            }
        });
    }

    /**
     * Scope for efficient feed loading with weighted scoring
     */
    public function scopeForFeed($query, $days = 30)
    {
        return $query->where('created_at', '>', now()->subDays($days))
            ->orderByRaw('(likes_count * 0.7 + comments_count * 0.3) DESC')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Scope for recent posts
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>', now()->subDays($days))
            ->orderBy('created_at', 'desc');
    }

    /**
     * Scope for posts by media type
     */
    public function scopeByMediaType($query, $type)
    {
        return $query->where('media_type', $type);
    }

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

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_likes')->withTimestamps();
    }

    public function isLikedBy(User $user): bool
    {
        // Optimize: Use direct query instead of relationship to avoid N+1
        return \DB::table('post_likes')
            ->where('post_id', $this->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    public function like(User $user)
    {
        if (!$this->isLikedBy($user)) {
            $this->likes()->attach($user->id);
            $this->increment('likes_count');
        }
    }

    public function unlike(User $user)
    {
        if ($this->isLikedBy($user)) {
            $this->likes()->detach($user->id);
            // Pastikan likes_count tidak menjadi negatif
            if ($this->likes_count > 0) {
                $this->decrement('likes_count');
            }
        }
    }

    public function savedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'saved_posts')->withTimestamps();
    }

    public function isSavedBy(User $user): bool
    {
        // Optimize: Use direct query instead of relationship to avoid N+1
        return \DB::table('saved_posts')
            ->where('post_id', $this->id)
            ->where('user_id', $user->id)
            ->exists();
    }

    public function saveBy(User $user)
    {
        if (!$this->isSavedBy($user)) {
            $this->savedBy()->attach($user->id);
        }
    }

    public function unsaveBy(User $user)
    {
        if ($this->isSavedBy($user)) {
            $this->savedBy()->detach($user->id);
        }
    }

    /**
     * Check if the current user can delete this post
     */
    public function canBeDeletedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }
}
