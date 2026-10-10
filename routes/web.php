<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/search', [MarketplaceController::class, 'search'])->name('marketplace.search');
Route::get('/category/{category}', [MarketplaceController::class, 'category'])->name('marketplace.category');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('/campuses', [CampusController::class, 'index'])->name('campuses.index');
Route::get('/campuses/{campus}', [CampusController::class, 'show'])->name('campuses.show');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/contact', [PageController::class, 'contact'])->name('contact.page');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit')->middleware('throttle:10,1');

/*
|--------------------------------------------------------------------------
| Guest authentication routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:10,1');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:10,1');

    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated student routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Student ID verification
    Route::get('/verification', [VerificationController::class, 'status'])->name('verification.status');
    Route::get('/verification/submit', [VerificationController::class, 'create'])->name('verification.create');
    Route::post('/verification', [VerificationController::class, 'store'])->name('verification.store')->middleware('throttle:10,1');
    Route::get('/verification/history', [VerificationController::class, 'history'])->name('verification.history');
    Route::get('/verification/{verificationRequest}/document/{side}', [VerificationController::class, 'document'])
        ->name('verification.document');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    /*
    |----------------------------------------------------------------------
    | Marketplace & order routes (verified students; administrators allowed)
    |----------------------------------------------------------------------
    */
    Route::middleware('verified.student')->group(function () {
        // Wishlist
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
        Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
        Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

        // Checkout & orders (Cash on Delivery only)
        Route::get('/checkout/{product}', [CheckoutController::class, 'create'])->name('checkout.create');
        Route::post('/checkout/{product}', [CheckoutController::class, 'store'])->name('checkout.store');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}/confirmation', [OrderController::class, 'confirmation'])->name('orders.confirmation');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::post('/orders/{order}/review', [ReviewController::class, 'store'])->name('orders.review');

    });

    /*
    |----------------------------------------------------------------------
    | Messaging (verified students; administrators allowed for oversight)
    |----------------------------------------------------------------------
    */
    Route::middleware('verified.student')->group(function () {
        Route::get('/messages', [ConversationController::class, 'index'])->name('messages.index');
        Route::post('/messages/start/{product}', [ConversationController::class, 'start'])->name('messages.start');
        Route::get('/messages/{conversation}', [ConversationController::class, 'show'])->name('messages.show');
        Route::post('/messages/{conversation}', [ConversationController::class, 'storeMessage'])->name('messages.store');
        Route::post('/messages/{conversation}/report', [ConversationController::class, 'report'])->name('messages.report');
    });

    /*
    |----------------------------------------------------------------------
    | Verified-student seller area (administrators allowed for oversight)
    |----------------------------------------------------------------------
    */
    Route::middleware('verified.student')->prefix('seller')->name('seller.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Seller\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/products', [\App\Http\Controllers\Seller\ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [\App\Http\Controllers\Seller\ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [\App\Http\Controllers\Seller\ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [\App\Http\Controllers\Seller\ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [\App\Http\Controllers\Seller\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [\App\Http\Controllers\Seller\ProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/orders', [\App\Http\Controllers\Seller\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [\App\Http\Controllers\Seller\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [\App\Http\Controllers\Seller\OrderController::class, 'updateStatus'])->name('orders.status');
    });
});

/*
|--------------------------------------------------------------------------
| Public seller profile
|--------------------------------------------------------------------------
| Registered after the authenticated /seller/* area so this wildcard does
| not shadow routes such as /seller/dashboard, /seller/products, /seller/orders.
*/

Route::get('/seller/{user}', [SellerController::class, 'show'])->name('sellers.show');

/*
|--------------------------------------------------------------------------
| Administrator routes
|--------------------------------------------------------------------------
*/

require __DIR__ . '/admin.php';
