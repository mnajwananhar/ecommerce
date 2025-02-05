<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\SellerRequestController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\SocialiteController;

Route::get('/', [ProductController::class, 'welcome'])->name('welcome');

// Destination routes
Route::get('/test-shop/{shop}', function (\App\Models\Shop $shop) {
    dd($shop);
});
Route::get('search-destination', [DestinationController::class, 'search'])->name('destination.search');
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google-callback', [SocialiteController::class, 'handleGoogleCallback'])->name('google.callback');

// Shop routes
Route::get('/shops/{shop:shop_name}', [ShopController::class, 'show'])->name('shops.show');

// Products routes
Route::resource('products', ProductController::class)->only(['index', 'show']);
Route::post('/products/{product}/cart', [ProductController::class, 'addToCart'])->name('products.addToCart');
Route::get('/products/{product}/quick-view', [ProductController::class, 'quickView'])->name('products.quickView');
Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.filter');
// Authenticated routes

Route::prefix('seller/products')->group(function () {
    Route::get('manage', [ProductController::class, 'listproduct'])->name('products.manage.index');
    Route::get('manage/create', [ProductController::class, 'create'])->name('products.manage.create');
    Route::post('manage', [ProductController::class, 'store'])->name('products.manage.store');
    Route::get('manage/{id}/edit', [ProductController::class, 'edit'])->name('products.manage.edit');
    Route::put('manage/{id}', [ProductController::class, 'update'])->name('products.manage.update');
    Route::delete('manage/{id}', [ProductController::class, 'destroy'])->name('products.manage.destroy');
    Route::post('manage/{id}/restore', [ProductController::class, 'restore'])->name('products.manage.restore');
    Route::delete('manage/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.manage.force-delete');
});

Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::middleware('auth')->group(function () {



    // Cart routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('add/{product}', [CartController::class, 'add'])->name('cart.add');
        Route::delete('remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
        Route::post('{cartItem}/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
        Route::get('checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    });
    // Orders routes
    Route::prefix('orders')->group(function () {
        Route::get('checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
        Route::get('history', [OrderController::class, 'history'])->name('orders.history');
        Route::get('{order}/details', [OrderController::class, 'details'])->name('orders.details');
        Route::post('store', [OrderController::class, 'store'])->name('orders.store');

        Route::post('calculate-shipping', [OrderController::class, 'calculateShipping'])->name('calculate.shipping');
    });

    // Reviews routes
    Route::prefix('reviews')->group(function () {
        Route::get('create', [ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/', [ReviewController::class, 'store'])->name('reviews.store');
        Route::get('{order}/show', [ReviewController::class, 'show'])->name('reviews.show');
    });

    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('/photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.photo.update'); // New route for photo upload
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });


    Route::get('/seller-request', [SellerRequestController::class, 'create'])->name('seller-request.create');
    Route::post('/seller-request', [SellerRequestController::class, 'store'])->name('seller-request.store');

    // Role-specific routes
    Route::middleware('role:customer')->group(function () {

        Route::get('/destination', [DestinationController::class, 'index'])->name('destination.index');
    });

    // Role-specific routes
    Route::middleware('role:seller')->group(function () {
        Route::get('seller/dashboard', [DashboardController::class, 'sellerDashboard'])->name('seller.dashboard');
        Route::prefix('seller/orders')->group(function () {
            Route::get('manage', [OrderController::class, 'manage'])->name('orders.manage');
            Route::post('{order}/accept', [OrderController::class, 'accept'])->name('orders.accept');
            Route::post('{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');
        });





        Route::prefix('seller/shops')->group(function () {
            Route::get('/create', [ShopController::class, 'create'])->name('shops.create');
            Route::get('/search-destination', [ShopController::class, 'searchDestination'])->name('shops.search-destination');
            Route::get('/', [ShopController::class, 'index'])->name('shops.index');
            Route::post('/', [ShopController::class, 'store'])->name('shops.store');
            Route::get('/{shop:shop_name}/edit', [ShopController::class, 'edit'])->name('shops.edit');
            Route::patch('/{shop:shop_name}', [ShopController::class, 'update'])->name('shops.update');
            Route::delete('/{shop:shop_name}', [ShopController::class, 'destroy'])->name('shops.destroy');
        });

        // Removed categories resource from here
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
        Route::prefix('seller-requests')->group(function () {
            Route::get('/', [SellerRequestController::class, 'index'])->name('seller-requests.index');
            Route::post('{sellerRequest}/approve', [SellerRequestController::class, 'approve'])->name('seller-requests.approve');
            Route::post('{sellerRequest}/reject', [SellerRequestController::class, 'reject'])->name('seller-requests.reject');
        });

        // Added categories resource here
        Route::resource('categories', CategoryController::class);
    });
});

require __DIR__ . '/auth.php';
