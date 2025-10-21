<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    public function index()
    {
        $baseUrl = url('/');

        $staticUrls = [
            [
                'loc' => $baseUrl,
                'changefreq' => 'daily',
                'priority' => '1.0',
                'lastmod' => now()->toAtomString(),
            ],
            [
                'loc' => url('/search'),
                'changefreq' => 'daily',
                'priority' => '0.8',
                'lastmod' => now()->toAtomString(),
            ],
        ];

        // Cache sitemap data for 15 minutes
        $urls = Cache::remember('sitemap.urls', 900, function () {
            $entries = [];

            // Latest public share pages for posts
            $latestPosts = Post::query()
                ->orderByDesc('updated_at')
                ->limit(200)
                ->get();

            foreach ($latestPosts as $post) {
                $entries[] = [
                    'loc' => route('posts.share', $post),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                    'lastmod' => optional($post->updated_at)->toAtomString(),
                ];
            }

            // Latest user profile pages
            $latestUsers = User::query()
                ->orderByDesc('updated_at')
                ->limit(200)
                ->get(['username', 'updated_at']);

            foreach ($latestUsers as $user) {
                if (!empty($user->username)) {
                    $entries[] = [
                        'loc' => route('profile.show', ['username' => $user->username]),
                        'changefreq' => 'weekly',
                        'priority' => '0.6',
                        'lastmod' => optional($user->updated_at)->toAtomString(),
                    ];
                }
            }

            return $entries;
        });

        $allUrls = array_merge($staticUrls, $urls);

        return response()
            ->view('sitemap', ['urls' => $allUrls])
            ->header('Content-Type', 'application/xml');
    }
}


