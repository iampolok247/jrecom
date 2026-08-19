<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HomepageController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SizeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PaymentlyWebhookController;
use App\Http\Controllers\Frontend\ProductDetailController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\UserDashboardController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// Public Storefront Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/newsletter/subscribe', [HomeController::class, 'subscribe'])->name('newsletter.subscribe');

// Media & Storage Asset Delivery Routes (Serves uploaded product images reliably on all hosting environments)
Route::get('/uploads/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);

    if (!file_exists($filePath)) {
        $clean = ltrim(str_replace(['storage/', 'uploads/'], '', $path), '/');
        $altPath = storage_path('app/public/' . $clean);
        if (file_exists($altPath)) {
            $filePath = $altPath;
        }
    }

    if (!file_exists($filePath)) {
        return redirect('https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80');
    }

    return response()->file($filePath);
})->where('path', '.*')->name('uploads.file');

Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);

    if (!file_exists($filePath)) {
        $clean = ltrim(str_replace(['storage/', 'uploads/'], '', $path), '/');
        $altPath = storage_path('app/public/' . $clean);
        if (file_exists($altPath)) {
            $filePath = $altPath;
        }
    }

    if (!file_exists($filePath)) {
        return redirect('https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80');
    }

    return response()->file($filePath);
})->where('path', '.*')->name('storage.file');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/search/suggestions', [ShopController::class, 'searchSuggestions'])->name('search.suggestions');

Route::get('/product/{slug}', [ProductDetailController::class, 'show'])->name('product.detail');
Route::post('/product/{id}/review', [ProductDetailController::class, 'submitReview'])->name('product.review');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::get('/cart/mini', [CartController::class, 'miniCart'])->name('cart.mini');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

// Paymently.io API Webhook & Callbacks
Route::post('/paymently/webhook', [PaymentlyWebhookController::class, 'handleWebhook'])->name('paymently.webhook');
Route::get('/paymently/callback/{order}', [PaymentlyWebhookController::class, 'callback'])->name('paymently.callback');
Route::get('/paymently/mock-gateway/{order}', [PaymentlyWebhookController::class, 'mockGateway'])->name('paymently.mock_gateway');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Customer Dashboard Routes
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [UserDashboardController::class, 'orders'])->name('orders');
    Route::get('/orders/{orderNumber}', [UserDashboardController::class, 'orderDetail'])->name('orders.detail');
    Route::get('/invoice/{orderNumber}', [UserDashboardController::class, 'printInvoice'])->name('invoice');
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/wishlist', [UserDashboardController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/toggle/{productId}', [UserDashboardController::class, 'toggleWishlist'])->name('wishlist.toggle');
});

// Admin Panel Routes
Route::middleware([AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', AdminProductController::class);
    
    // Categories & Brands & Colors & Sizes
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);
    Route::resource('brands', BrandController::class)->only(['index', 'store', 'destroy']);
    Route::resource('colors', ColorController::class)->only(['index', 'store', 'destroy']);
    Route::resource('sizes', SizeController::class)->only(['index', 'store', 'destroy']);
    
    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update_status');
    Route::get('/orders/{id}/invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.invoice');
    
    // Payment Gateway & Paymently.io API Management
    Route::get('/payment-gateways', [PaymentGatewayController::class, 'index'])->name('payment.index');
    Route::post('/payment-gateways/paymently', [PaymentGatewayController::class, 'updatePaymently'])->name('payment.update_paymently');
    Route::post('/payment-gateways/method/{id}', [PaymentGatewayController::class, 'updateMethod'])->name('payment.update_method');
    
    // Homepage Management
    Route::get('/homepage-builder', [HomepageController::class, 'index'])->name('homepage.index');
    Route::post('/homepage-builder/sections', [HomepageController::class, 'updateSections'])->name('homepage.update_sections');
    Route::post('/homepage-builder/banner', [HomepageController::class, 'storeBanner'])->name('homepage.store_banner');
    Route::delete('/homepage-builder/banner/{id}', [HomepageController::class, 'destroyBanner'])->name('homepage.destroy_banner');
    
    // Site Settings & Appearance
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    
    // Coupons & Customers
    Route::resource('coupons', CouponController::class)->only(['index', 'store', 'destroy']);
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers/{id}/status', [CustomerController::class, 'toggleStatus'])->name('customers.toggle_status');
    
    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity.index');
});
