<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'profile_url',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Generate a unique username from the user's name
     */
    public static function generateUsername(string $name): string
    {
        // Convert name to lowercase and replace spaces with dots
        $baseUsername = Str::of($name)
            ->lower()
            ->replace(' ', '.')
            ->replaceMatches('/[^a-z0-9.]/', '')
            ->toString();

        // Remove consecutive dots and trim dots from start/end
        $baseUsername = preg_replace('/\.+/', '.', $baseUsername);
        $baseUsername = trim($baseUsername, '.');

        // If empty after cleaning, use 'user'
        if (empty($baseUsername)) {
            $baseUsername = 'user';
        }

        return $baseUsername;
    }

    /**
     * Generate a unique username from the user's name, checking for uniqueness
     */
    public static function generateUniqueUsername(string $name): string
    {
        $baseUsername = static::generateUsername($name);
        $username = $baseUsername;
        $counter = 1;

        // Check if username exists and add number if needed
        while (static::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }

    /**
     * Generate profile URL from username
     */
    public function generateProfileUrl(): string
    {
        return url("/profile/{$this->username}");
    }

    /**
     * Get the user's posts
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
