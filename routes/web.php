<?php

use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShareController;
use App\Livewire\Home\HomePage;
use App\Livewire\Posts\PostDetail;
use App\Livewire\Profile\UserProfile;
use App\Livewire\Search\SearchPage;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\Notifications\NotificationList;
use App\Livewire\Guest\GuestHomePage;
use App\Livewire\Guest\GuestSearchPage;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// Guest routes (accessible without authentication, but redirect authenticated users)
Route::middleware('prevent.authenticated')->group(function () {
    Route::get('/', GuestHomePage::class)->name('guest.home');
    Route::get('/search', GuestSearchPage::class)->name('guest.search');
});

Route::get('/profile/{username}', UserProfile::class)->name('profile.show');

// Error page testing routes (remove in production)
Route::get('/test/404', function () {
    abort(404);
})->name('test.404');

Route::get('/test/403', function () {
    abort(403);
})->name('test.403');

Route::get('/test/419', function () {
    abort(419);
})->name('test.419');

Route::get('/test/429', function () {
    abort(429);
})->name('test.429');

Route::get('/test/500', function () {
    abort(500);
})->name('test.500');

Route::get('/test/503', function () {
    abort(503);
})->name('test.503');

// Public share route - accessible without authentication
Route::get('/share/{post}', [ShareController::class, 'show'])->name('posts.share');
// Copy event endpoint (auth optional). If authed and not owner, notify owner
Route::post('/share/{post}/copied', [ShareController::class, 'copied'])->name('posts.share.copied');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', HomePage::class)->name('homePage');
    Route::get('/app/search', SearchPage::class)->name('searchPage');
    Route::get('/notifications', NotificationList::class)->name('notifications');
    Route::get('/posts', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', PostDetail::class)->name('posts.show');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Follow routes
    Route::post('/users/{user}/follow', [FollowController::class, 'follow'])->name('users.follow');
    Route::delete('/users/{user}/unfollow', [FollowController::class, 'unfollow'])->name('users.unfollow');
    Route::post('/users/{user}/toggle-follow', [FollowController::class, 'toggle'])->name('users.toggle-follow');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // Profile image routes
    Route::post('profile/upload-image', [ProfileController::class, 'uploadProfileImage'])->name('profile.upload-image');
    Route::delete('profile/delete-image', [ProfileController::class, 'deleteProfileImage'])->name('profile.delete-image');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
