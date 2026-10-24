<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\OrderController as AccountOrderController;
use App\Http\Controllers\Account\AddressController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ShippingRateController;
use App\Http\Controllers\Admin\ReviewController;
use Illuminate\Support\Facades\Route;

// =========================================================
// TRANG CHỦ & DANH MỤC & SẢN PHẨM (PUBLIC)
// =========================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/san-pham', [ProductController::class, 'index'])->name('products.index');
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/danh-muc/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// =========================================================
// XÁC THỰC (Guest only)
// =========================================================
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [LoginController::class, 'create'])->name('login');
    Route::post('/dang-nhap', [LoginController::class, 'store'])->name('login.store');
    Route::get('/dang-ky', [RegisterController::class, 'create'])->name('register');
    Route::post('/dang-ky', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/quen-mat-khau', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/quen-mat-khau', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/dat-lai-mat-khau/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/dat-lai-mat-khau', [ResetPasswordController::class, 'store'])->name('password.store');
    Route::redirect('/login', '/dang-nhap');
    Route::redirect('/register', '/dang-ky');
});

Route::post('/dang-xuat', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// =========================================================
// GIỎ HÀNG (không cần đăng nhập)
// =========================================================
Route::prefix('gio-hang')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/them', [CartController::class, 'add'])->name('add');
    Route::patch('/cap-nhat', [CartController::class, 'update'])->name('update');
    Route::delete('/xoa', [CartController::class, 'remove'])->name('remove');
    Route::delete('/xoa-tat-ca', [CartController::class, 'clear'])->name('clear');
});

// Route aliases cho AJAX trong app.js
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'add']);
    Route::patch('/update', [CartController::class, 'update']);
    Route::delete('/remove', [CartController::class, 'remove']);
    Route::delete('/clear', [CartController::class, 'clear']);
});

// =========================================================
// THANH TOÁN (cần đăng nhập)
// =========================================================
Route::prefix('thanh-toan')->name('checkout.')->middleware('auth')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::get('/thanh-cong/{order}', [CheckoutController::class, 'success'])->name('success');
    // VNPay callbacks
    Route::get('/vnpay/return', [CheckoutController::class, 'vnpayReturn'])->name('vnpay.return');
    Route::post('/vnpay/ipn', [CheckoutController::class, 'vnpayIpn'])->name('vnpay.ipn')->withoutMiddleware('auth');
});

// =========================================================
// TÀI KHOẢN KHÁCH HÀNG (cần đăng nhập)
// =========================================================
Route::prefix('tai-khoan')->name('account.')->middleware('auth')->group(function () {
    Route::get('/ho-so', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/ho-so', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/doi-mat-khau', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::resource('don-hang', AccountOrderController::class)
        ->only(['index', 'show'])
        ->names(['index' => 'orders.index', 'show' => 'orders.show']);
    Route::post('/don-hang/{order}/huy', [AccountOrderController::class, 'cancel'])->name('orders.cancel');

    Route::resource('dia-chi', AddressController::class)
        ->names([
            'index' => 'addresses.index',
            'create' => 'addresses.create',
            'store' => 'addresses.store',
            'edit' => 'addresses.edit',
            'update' => 'addresses.update',
            'destroy' => 'addresses.destroy',
        ]);
    Route::patch('/dia-chi/{address}/mac-dinh', [AddressController::class, 'setDefault'])->name('addresses.setDefault');
});

// =========================================================
// TRANG QUẢN TRỊ (Admin only)
// =========================================================
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('danh-muc', AdminCategoryController::class)->parameters(['danh-muc' => 'category'])
        ->names([
            'index' => 'categories.index', 'create' => 'categories.create',
            'store' => 'categories.store', 'edit' => 'categories.edit',
            'update' => 'categories.update', 'destroy' => 'categories.destroy',
        ]);

    Route::resource('san-pham', AdminProductController::class)->parameters(['san-pham' => 'product'])
        ->names([
            'index' => 'products.index', 'create' => 'products.create',
            'store' => 'products.store', 'show' => 'products.show',
            'edit' => 'products.edit', 'update' => 'products.update',
            'destroy' => 'products.destroy',
        ]);
    Route::delete('/san-pham/{product}/anh/{image}', [AdminProductController::class, 'destroyImage'])
        ->name('products.images.destroy');

    Route::resource('don-hang', AdminOrderController::class)->parameters(['don-hang' => 'order'])
        ->names([
            'index' => 'orders.index', 'show' => 'orders.show',
            'edit' => 'orders.edit', 'update' => 'orders.update',
        ])->except(['create', 'store', 'destroy']);
    Route::patch('/don-hang/{order}/trang-thai', [AdminOrderController::class, 'updateStatus'])
        ->name('orders.updateStatus');

    Route::get('/thanh-toan', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/thanh-toan/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');

    Route::resource('khach-hang', CustomerController::class)->parameters(['khach-hang' => 'customer'])
        ->names([
            'index' => 'customers.index', 'show' => 'customers.show',
        ])->except(['create', 'store', 'edit', 'update', 'destroy']);
    Route::patch('/khach-hang/{customer}/toggle-active', [CustomerController::class, 'toggleActive'])
        ->name('customers.toggleActive');

    Route::resource('ma-giam-gia', CouponController::class)->parameters(['ma-giam-gia' => 'coupon'])
        ->names([
            'index' => 'coupons.index', 'create' => 'coupons.create',
            'store' => 'coupons.store', 'edit' => 'coupons.edit',
            'update' => 'coupons.update', 'destroy' => 'coupons.destroy',
        ]);

    Route::resource('phi-van-chuyen', ShippingRateController::class)->parameters(['phi-van-chuyen' => 'shippingRate'])
        ->names([
            'index' => 'shipping-rates.index', 'create' => 'shipping-rates.create',
            'store' => 'shipping-rates.store', 'edit' => 'shipping-rates.edit',
            'update' => 'shipping-rates.update', 'destroy' => 'shipping-rates.destroy',
        ]);

    Route::get('/danh-gia', [ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/danh-gia/{review}/duyet', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/danh-gia/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});
