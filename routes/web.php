<?php

use App\Http\Controllers\Admin\ServiceCrudController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\TwoFactor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

// Authenticated Admin
Route::middleware([EnsureUserIsAdmin::class, TwoFactor::class])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/accueil/carousel', CarouselController::class);

    Route::resource('/categories', CategoryController::class)->except('moveUp', 'moveDown', 'orderIndex');

    Route::get('/accueil/categories', [CategoryController::class, 'orderIndex'])->name('category.orderIndex');
    Route::get('/accueil/categories/{id}/up', [CategoryController::class, 'moveUp'])->name('category.up');
    Route::get('/accueil/categories/{id}/down', [CategoryController::class, 'moveDown'])->name('category.down');
    

    Route::resource('/services', ServiceController::class)->except('moveUp', 'moveDown', 'topProducts', 'reorderTop');
    Route::get('/services/{id}/up', [ServiceController::class, 'moveUp'])->name('service.up');
    Route::get('/services/{id}/down', [ServiceController::class, 'moveDown'])->name('service.down');

    Route::get('/accueil/top-products', [ServiceController::class, 'topProducts'])->name('service.topProducts');
    Route::get('/accueil/top-products/{id}/move-up-top', [ServiceController::class, 'moveUpTopProduct'])->name('service.moveUpTop');
    Route::get('/accueil/top-products/{id}/move-down-top', [ServiceController::class, 'moveDownTopProduct'])->name('service.moveDownTop');

    
    Route::resource('/support', SupportController::class);
    // Route::get('/support', [SupportController::class, 'showForm'])->name('support.form');
    // Route::post('/support', [SupportController::class, 'submitForm'])->name('support.submit');

    Route::resource('/users', UserController::class);

    Route::get('/accueil', function () {
        return view('accueil');
    })->name('accueil');
});

// Route::middleware('auth')->group(function () {
Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
Route::resource('verify', TwoFactorController::class)->only(['index', 'store']);
// });

// Page CGE accessible à tous
Route::get('/cge', function () {
    return view('cge.cge');
})->name('cge');

require __DIR__ . '/auth.php';
