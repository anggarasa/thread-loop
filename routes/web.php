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
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/profile/{username}', UserProfile::class)->name('profile.show');

// Public share route - accessible without authentication
Route::get('/share/{post}', [ShareController::class, 'show'])->name('posts.share');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', HomePage::class)->name('homePage');
    Route::get('/search', SearchPage::class)->name('searchPage');
    Route::get('/posts', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}', PostDetail::class)->name('posts.show');

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
