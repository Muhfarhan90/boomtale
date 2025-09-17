<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [AuthController::class, 'handleAuthGoogleCallback']);

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::name('user.')->group(function () {
    // Public Home Routes
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/search', [HomeController::class, 'search'])->name('search');

    // Public Review Routes
    Route::get('/products/{product}/reviews', [ReviewController::class, 'getReviews'])->name('products.reviews');


    // Public Product Routes
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/search', [ProductController::class, 'search'])->name('search');
        Route::get('/{product:slug}', [ProductController::class, 'show'])->name('show');
    });

    // Public Category Routes
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{category:slug}', [CategoryController::class, 'show'])->name('show');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::delete('/delete', [ProfileController::class, 'delete'])->name('delete');
    });

    Route::get('/orders', [UserController::class, 'showOrders'])->name('orders.index');

    // Authenticated User Routes
    Route::middleware('auth')->group(function () {
        // Cart routes
        Route::prefix('cart')->name('cart.')->group(function () {
            Route::get('/', [CartController::class, 'index'])->name('index');
            Route::post('/add', [CartController::class, 'add'])->name('add');
            Route::put('/{cart}', [CartController::class, 'update'])->name('update'); // {cart} parameter
            Route::delete('/{cart}', [CartController::class, 'remove'])->name('remove'); // {cart} parameter
            Route::delete('/', [CartController::class, 'clear'])->name('clear');
            Route::get('/count', [CartController::class, 'count'])->name('count');
        });

        // Checkout routes
        Route::prefix('checkout')->name('checkout.')->group(function () {
            Route::get('/', [TransactionController::class, 'checkout'])->name('index');
            Route::post('/', [TransactionController::class, 'processCheckout'])->name('process');
            Route::post('/callback', [TransactionController::class, 'handleCallback'])->name('callback');
        });

        // Order routes
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::post('/', [OrderController::class, 'store'])->name('store');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
            Route::post('/{order}/reorder', [OrderController::class, 'reorder'])->name('reorder');
            Route::get('/{order}/download/{orderItem}', [OrderController::class, 'download'])->name('download');
        });

        Route::get('/orders/stats', [OrderController::class, 'getStatistics'])->name('orders.stats');

        // Order routes
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');


        // User Review Routes (Tied to OrderItem)
        Route::prefix('reviews')->name('reviews.')->group(function () {
            Route::get('/', [ReviewController::class, 'myReviews'])->name('index');
            Route::get('/create/{orderItem}', [ReviewController::class, 'create'])->name('create');
            Route::post('/store/{orderItem}', [ReviewController::class, 'store'])->name('store');
            Route::get('/{review}/edit', [ReviewController::class, 'edit'])->name('edit');
            Route::put('/{review}', [ReviewController::class, 'update'])->name('update');
            Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
        });

        // Route User Products
        Route::prefix('user-products')->name('user-products.')->group(function () {
            Route::get('/', [UserProductController::class, 'index'])->name('index');
            Route::get('/{userProduct}', [UserProductController::class, 'show'])->name('show');
            Route::get('/{userProduct}/download', [UserProductController::class, 'download'])->name('download');
            Route::get('/{userProduct}/stream', [UserProductController::class, 'stream'])->name('stream');
            Route::get('/{userProduct}/view-digital', [UserProductController::class, 'pdfStream'])->name('pdf-stream');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [UserController::class, 'bulkAction'])->name('bulk-action');
    });

    // Product Management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'adminIndex'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}', [ProductController::class, 'adminShow'])->name('show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        Route::patch('/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [ProductController::class, 'bulkAction'])->name('bulkAction');
        Route::get('/products/{product}/download', [ProductController::class, 'downloadDigitalFile'])->name('download');
    });

    // Category Management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'adminIndex'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [CategoryController::class, 'adminShow'])->name('show');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::patch('/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [CategoryController::class, 'bulkAction'])->name('bulk-action');
        Route::post('/reorder', [CategoryController::class, 'reorder'])->name('reorder');
    });

    // Review Management
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'adminIndex'])->name('index');
        Route::get('/{review}', [ReviewController::class, 'adminShow'])->name('show');
        Route::delete('/{review}', [ReviewController::class, 'adminDestroy'])->name('destroy');
        Route::post('/bulk-action', [ReviewController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/api/stats', [ReviewController::class, 'getStats'])->name('stats');
        Route::get('/api/recent/{limit?}', [ReviewController::class, 'getRecent'])->name('recent');
    });

    // Order
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'adminIndex'])->name('index');
        Route::get('/{order}', [OrderController::class, 'adminShow'])->name('show');
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
    });

    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/general', [SettingController::class, 'updateGeneral'])->name('update.general');
        Route::put('/system', [SettingController::class, 'updateSystem'])->name('update.system');
        Route::post('/maintenance', [SettingController::class, 'toggleMaintenance'])->name('toggle.maintenance');
        Route::post('/cache', [SettingController::class, 'clearCache'])->name('clear.cache');
        Route::post('/backup', [SettingController::class, 'backupDatabase'])->name('backup.database');
        Route::get('/system-info', [SettingController::class, 'systemInfo'])->name('system.info');
    });
});
