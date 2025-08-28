<?php

use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImagesServicesController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CartController;
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

    // 🔹 Stripe Paiements
    Route::get('/checkout', [StripeController::class, 'checkout'])->name('stripe.checkout');
    Route::get('/payment/create-test-order', [StripeController::class, 'createTestOrder'])->name('stripe.create-test-order');
    Route::get('/payment/success', [StripeController::class, 'success'])->name('stripe.success');
    Route::get('/payment/cancel', [StripeController::class, 'cancel'])->name('stripe.cancel');
    Route::post('/webhook/stripe', [StripeController::class, 'webhook'])->name('stripe.webhook');

    // 🔹 Panier -> Stripe Checkout
    Route::middleware('auth')->group(function () {
        Route::get('/order/billing-address', [OrderController::class, 'selectBillingAddress'])->name('order.select-billing-address');
        Route::post('/order/billing-address', [OrderController::class, 'storeSelectedBillingAddress'])->name('order.store-billing-address');
        Route::match(['GET', 'POST'], '/cart/checkout', [CartController::class, 'checkoutToStripe'])->name('cart.checkout');

        // 🔹 Panier
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/{id}', [CartController::class, 'update'])
            ->whereNumber('id')
            ->name('cart.update');
        Route::delete('/cart/{id}', [CartController::class, 'remove'])
            ->whereNumber('id')
            ->name('cart.remove');
        Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
        Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
        Route::post('/cart/order', [CartController::class, 'order'])->name('cart.order');
        Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])
            ->middleware('auth')
            ->name('cart.coupon.apply');
        Route::match(['POST', 'DELETE'], '/cart/coupon', [CartController::class, 'removeCoupon'])
            ->middleware('auth')
            ->name('cart.coupon.remove');
    });

    // 🔹 Commandes
    Route::get('/order/history', [OrderController::class, 'history'])->name('order.history');
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
        Route::put('/services/{service}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/service-images/{image}', [ImagesServicesController::class, 'destroy'])->name('service-images.destroy');
        Route::resource('/services', ServiceController::class)->except('moveUp', 'moveDown', 'topProducts', 'upadte', 'reorderTop', 'index', 'show');

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
            'destroy' => 'users.destroy',
        ]);

        Route::get('/orders', [OrderController::class, 'viewAdmin'])->name('orders.admin');


        // Admin Dashboard
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');

        Route::get('/stripe/test', function () {
            return view('stripe.test');
        })->name('stripe.test');
    });

// 🔹 2FA
Route::get('verify/resend', [TwoFactorController::class, 'resend'])->name('verify.resend');
Route::resource('verify', TwoFactorController::class)->only(['index', 'store']);

// 🔹 Pages publiques
Route::view('/cgu', 'cgu')->name('cgu');
Route::view('/mentions', 'mentions')->name('mentions');
Route::view('/contact', 'contact')->name('contact');
Route::view('/faq', 'faq')->name('faq');

// 🔹 Services & Catégories (publiques)
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{id}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');

// 🔹 Recherche
Route::get('/search', [SearchController::class, 'search'])->name('search');

// 🔹 Page d'accueil
Route::get('/', [CarouselController::class, 'index'])->name('home');

// Auth
require __DIR__ . '/auth.php';
