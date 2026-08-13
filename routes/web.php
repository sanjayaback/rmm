<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UnlockController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────────────────
Route::get('/', [ListingController::class, 'map'])->name('home');
Route::get('/browse', [ListingController::class, 'browse'])->name('listings.browse');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show')->whereNumber('listing');
Route::get('/api/listings/map', [ListingController::class, 'apiMap'])->middleware('throttle:60,1')->name('api.listings.map');
Route::get('/api/search', [SearchController::class, 'apiSearch'])->middleware('throttle:60,1')->name('api.search');

// Storage asset fallback for hosting environments missing symlinks
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*');

// ── Auth (Breeze) ─────────────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ── Authenticated ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'active'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Owner listing management
    Route::middleware('role:owner,admin')->group(function () {
        Route::get('/my-listings', [ListingController::class, 'index'])->name('listings.index');
        Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
        Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
        Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit')->whereNumber('listing');
        Route::put('/listings/{listing}', [ListingController::class, 'update'])->name('listings.update')->whereNumber('listing');
        Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->name('listings.destroy')->whereNumber('listing');
    });

    // Unlock / Payment
    Route::prefix('unlock')->name('unlocks.')->group(function () {
        Route::get('/history', [UnlockController::class, 'history'])->name('history');
        Route::get('/{listing}', [UnlockController::class, 'show'])->name('show');
        Route::post('/{listing}/initiate', [UnlockController::class, 'initiate'])->name('initiate');
        Route::get('/{listing}/{unlock}/payment', [UnlockController::class, 'payment'])->name('payment');
        Route::post('/{listing}/{unlock}/process', [UnlockController::class, 'process'])->name('process');
    });

    Route::get('/khalti/callback', [UnlockController::class, 'khaltiCallback'])->name('khalti.callback');
});

// ── Admin ─────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'active', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::prefix('listings')->name('listings.')->group(function () {
        Route::get('/', [AdminController::class, 'listings'])->name('index');
        Route::get('/{listing}', [AdminController::class, 'showListing'])->name('show');
        Route::get('/{listing}/edit', [AdminController::class, 'editListing'])->name('edit');
        Route::put('/{listing}', [AdminController::class, 'updateListing'])->name('update');
        Route::post('/{listing}/approve', [AdminController::class, 'approveListing'])->name('approve');
        Route::post('/{listing}/reject', [AdminController::class, 'rejectListing'])->name('reject');
        Route::delete('/{listing}', [AdminController::class, 'deleteListing'])->name('delete');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('index');
        Route::get('/{user}', [AdminController::class, 'showUser'])->name('show');
        Route::patch('/{user}/role', [AdminController::class, 'updateUserRole'])->name('role');
        Route::patch('/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('toggle-status');
    });

    Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('audit-logs.index');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings.index');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});
