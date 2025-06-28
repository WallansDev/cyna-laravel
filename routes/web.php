<?php

use App\Http\Controllers\Admin\ServiceCrudController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\TwoFactor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::view('/', 'home');

// Authenticated User
Route::middleware(['auth', 'verified'])->group(function () {

    // Profile
    Route::get('/users/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/users/profil/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/users/profil/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::view('/users/profil/edit/password', 'profile.changePassword')->name('password.edit');
    Route::put('/users/profil/edit', [ProfileController::class, 'update'])->name('profile.update');

    // Tickets
    Route::resource('/users/tickets', TicketController::class);
    Route::post('/users/tickets/{ticket}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/users/tickets/{ticket}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
});


// Authenticated Admin
Route::middleware([EnsureUserIsAdmin::class, TwoFactor::class, 'verified'])->prefix('admin')->group(function () {

    // Admin Carousel

    // Admin Categories
    Route::get('/categories', [CategoryController::class, 'viewAdmin'])->name('categories.viewAdmin');

    Route::get('/categories/order', [CategoryController::class, 'orderIndex'])->name('categories.orderIndex');
    Route::get('/categories/{id}/up', [CategoryController::class, 'moveUp'])->name('categories.up');
    Route::get('/categories/{id}/down', [CategoryController::class, 'moveDown'])->name('categories.down');
    Route::resource('/categories', CategoryController::class)->except('moveUp', 'moveDown', 'orderIndex', 'index', 'show');

    // Admin Services
    Route::get('/services', [ServiceController::class, 'viewAdmin'])->name('services.viewAdmin');
    Route::get('/services/{id}/up', [ServiceController::class, 'moveUp'])->name('services.up');
    Route::get('/services/{id}/down', [ServiceController::class, 'moveDown'])->name('services.down');
    Route::resource('/services', ServiceController::class)->except('moveUp', 'moveDown', 'topProducts', 'reorderTop', 'index', 'show');

    // Admin Top products
    Route::get('/services/order', [ServiceController::class, 'topProducts'])->name('services.topProducts');
    Route::get('/top-products/{id}/move-up-top', [ServiceController::class, 'moveUpTopProduct'])->name('services.moveUpTop');
    Route::get('/top-products/{id}/move-down-top', [ServiceController::class, 'moveDownTopProduct'])->name('services.moveDownTop');

    // Admin Users
    Route::resource('/users', UserController::class)->names([
        'index' => 'users.index',
        'create' => 'users.create',
        'store' => 'users.store',
        'show' => 'users.show',
        'edit' => 'users.edit',
        'update' => 'users.update',
        'destroy' => 'users.destroy',
    ]);

    // Admin Dashboard (listing all admin pages)
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// 2FA
Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
Route::resource('verify', TwoFactorController::class)->only(['index', 'store']);

// Page CGU accessible à tous
Route::get('/cgu', function () {
    return view('cgu');
})->name('cgu');

// Page Mentions légales accessible à tous
Route::get('/mentions', function () {
    return view('mentions');
})->name('mentions');

// Page Contact accessible à tous
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Page FAQ accessible à tous
Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/', [CarouselController::class, 'index'])->name('home');

require __DIR__ . '/auth.php';
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/', [CarouselController::class, 'index'])->name('home');

require __DIR__ . '/auth.php';
