<?php

use App\Http\Controllers\BusinessProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RatingController;
use App\Livewire\BizReview;
use App\Livewire\BizService;
use App\Livewire\Buzprofile;
use App\Livewire\MyBiz;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');




Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    Route::redirect('settings', 'settings/profile');

    Route::get('business/profile', Buzprofile::class)->name('profile');
    Route::get('business/services', BizService::class)->name('services');
    Route::get('business/reviews', BizReview::class)->name('reviews');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

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

Route::post('/ratings', [RatingController::class, 'store'])->middleware('auth')->name('ratings.store');


Route::get('/business/{slug}', [BusinessProfileController::class, 'show'])->middleware('track')->name('business.show');

Route::get('/my-business', \App\Livewire\MyBiz::class)->name('my-business');;

require __DIR__.'/auth.php';
