<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CampusController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CodController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VerificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware('admin')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.status');

        // Student ID verification
        Route::get('/verifications', [VerificationController::class, 'index'])->name('verifications.index');
        Route::get('/verifications/{verification}', [VerificationController::class, 'show'])->name('verifications.show');
        Route::post('/verifications/{verification}/approve', [VerificationController::class, 'approve'])->name('verifications.approve');
        Route::post('/verifications/{verification}/reject', [VerificationController::class, 'reject'])->name('verifications.reject');
        Route::post('/verifications/{verification}/resubmission', [VerificationController::class, 'requestResubmission'])->name('verifications.resubmission');

        // Product moderation
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::post('/products/{product}/approve', [ProductController::class, 'approve'])->name('products.approve');
        Route::post('/products/{product}/reject', [ProductController::class, 'reject'])->name('products.reject');
        Route::post('/products/{product}/remove', [ProductController::class, 'remove'])->name('products.remove');

        // Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Campuses
        Route::get('/campuses', [CampusController::class, 'index'])->name('campuses.index');
        Route::post('/campuses', [CampusController::class, 'store'])->name('campuses.store');
        Route::put('/campuses/{campus}', [CampusController::class, 'update'])->name('campuses.update');
        Route::delete('/campuses/{campus}', [CampusController::class, 'destroy'])->name('campuses.destroy');

        // Orders
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

        // COD payment tracking
        Route::get('/cod', [CodController::class, 'index'])->name('cod.index');
        Route::post('/cod/{order}/mark-paid', [CodController::class, 'markPaid'])->name('cod.markPaid');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::patch('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');

        // Reviews
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/toggle', [ReviewController::class, 'toggle'])->name('reviews.toggle');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

        // Inquiries
        Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
        Route::patch('/inquiries/{inquiry}', [InquiryController::class, 'update'])->name('inquiries.update');

        // Audit logs
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit.index');
    });
