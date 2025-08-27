<?php

use App\Http\Controllers\Admin\ServiceCrudController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesServicesController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserStatsController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\TwoFactor;
use Illuminate\Support\Facades\Route;


// 🔹 Utilisateurs authentifiés
Route::middleware(['auth', 'verified'])->group(function () {
    // Profil
    Route::get('/users/profil', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/users/profil/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/users/profil/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::view('/users/profil/edit/password', 'profile.changePassword')->name('password.edit');
    Route::put('/users/profil/edit', [ProfileController::class, 'update']);

    // Billing Addresses
    Route::get('/users/profil/billing-addresses', [UserController::class, 'billingAddresses'])->name('billing-addresses.index');
    Route::post('/users/profil/billing-addresses', [UserController::class, 'storeBillingAddress'])->name('billing-addresses.store');
    Route::put('/users/profil/billing-addresses/{id}', [UserController::class, 'updateBillingAddress'])->name('billing-addresses.update');
    Route::delete('/users/profil/billing-addresses/{id}', [UserController::class, 'destroyBillingAddress'])->name('billing-addresses.destroy');

    // Tickets
    Route::resource('/users/tickets', TicketController::class);
    Route::post('/users/tickets/{ticket}/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/users/tickets/{ticket}/update-status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

// 🔹 Admin
Route::middleware([EnsureUserIsAdmin::class, TwoFactor::class, 'verified'])
    ->prefix('admin')
    ->group(function () {
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
        Route::resource('/services', ServiceController::class)->except('moveUp', 'moveDown', 'topProducts', 'upadte', 'reorderTop', 'index', 'show');
        // Route::delete('/service-images/{image}', [ImagesServicesController::class, 'destroy'])->name('service-images.destroy');
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/service-images/{image}', [ImagesServicesController::class, 'destroy'])->name('service-images.destroy');
        
        // Admin Top products
        Route::get('/services/top', [ServiceController::class, 'topProducts'])->name('services.topProducts');
        Route::get('/top-products/{id}/move-up-top', [ServiceController::class, 'moveUpTopProduct'])->name('services.moveUpTop');
        Route::get('/top-products/{id}/move-down-top', [ServiceController::class, 'moveDownTopProduct'])->name('services.moveDownTop');

        
        // Admin User Stats
        Route::get('/users/stats', [UserStatsController::class, 'index'])->name('users.stats');

        // Admin Users
        Route::resource('/users', UserController::class)->names([
            'index' => 'users.index',
            'create' => 'users.create',
            'store' => 'users.store',
            'show' => 'users.show',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'destroy' => 'users.destroy'
        ]);

        // Admin Dashboard
        Route::get('/dashboard', fn () => view('admin.dashboard'))->name('admin.dashboard');

    });

// 🔹 2FA
Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
Route::resource('verify', TwoFactorController::class)->only(['index', 'store']);

// 🔹 Pages publiques
Route::view('/cgu', 'cgu')->name('cgu');
Route::view('/mentions', 'mentions')->name('mentions');
Route::view('/contact', 'contact')->name('contact');
Route::view('/faq', 'faq')->name('faq');

// 🔹 Panier
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::post('/cart/order', [CartController::class, 'order'])->name('cart.order');

// 🔹 Services & Catégories (publiques)
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');

// 🔹 Recherche
Route::get('/search', [SearchController::class, 'search'])->name('search');

// 🔹 Stripe Paiements
Route::get('/checkout', [StripeController::class, 'checkout'])->name('stripe.checkout');
Route::get('/payment/create-test-order', [StripeController::class, 'createTestOrder'])->name('stripe.create-test-order');
Route::get('/payment/success', [StripeController::class, 'success'])->name('stripe.success');
Route::get('/payment/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
Route::post('/webhook/stripe', [StripeController::class, 'webhook'])->name('stripe.webhook');
Route::get('/stripe/test', function() {
    return view('stripe.test');
})->name('stripe.test');

// 🔹 Commandes
Route::get('/order/checkout', [OrderController::class, 'checkout'])->name('order.checkout');
Route::post('/order/process', [OrderController::class, 'processOrder'])->name('order.process');
Route::get('/order/confirmation', [OrderController::class, 'confirmation'])->name('order.confirmation');
Route::get('/order/history', [OrderController::class, 'history'])->name('order.history');

// 🔹 Page d'accueil
Route::get('/', [CarouselController::class, 'index'])->name('home');

// Auth
require __DIR__ . '/auth.php';