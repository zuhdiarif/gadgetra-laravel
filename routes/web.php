<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [ProductController::class, 'detail'])->name('product.detail')
    ->where('slug', '[a-z0-9\-]+');

Route::middleware(['guest', 'throttle:10,1'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar');
    Route::get('/profile/data', [ProfileController::class, 'getData'])->name('profile.data');
    Route::get('/profile/rentals', [ProfileController::class, 'rentals'])->name('rentals.index');
    Route::get('/payment/method', [PaymentController::class, 'method'])->name('payment.method');
    Route::get('/payment/instruction', [PaymentController::class, 'instruction'])->name('payment.instruction');
    Route::get('/booking/code', [PaymentController::class, 'bookingCode'])->name('booking.code');
    Route::post('/booking/store', [PaymentController::class, 'storeBooking'])->name('booking.store');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/{id}/update', [CartController::class, 'updateCart'])->name('cart.update')->where('id', '[0-9]+');
    Route::delete('/cart/{id}', [CartController::class, 'deleteCart'])->name('cart.delete')->where('id', '[0-9]+');

    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/transactions', [AdminController::class, 'transactions'])->name('admin.transactions');
        Route::get('/admin/transactions/{code}', [AdminController::class, 'transactionDetail'])->name('admin.transactions.show')
            ->where('code', 'RNT[A-Z0-9]+');
        Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
        Route::get('/admin/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
        Route::post('/admin/products/store', [AdminController::class, 'storeProduct'])->name('admin.products.store');
        Route::get('/admin/products/{id}/edit', [AdminController::class, 'editProduct'])->name('admin.products.edit')
            ->where('id', '[0-9]+');
        Route::put('/admin/products/{id}/update', [AdminController::class, 'updateProduct'])->name('admin.products.update')
            ->where('id', '[0-9]+');
        Route::delete('/admin/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete')
            ->where('id', '[0-9]+');
        Route::get('/admin/products/returns', [AdminController::class, 'returns'])->name('admin.products.returns');
        Route::post('/admin/products/returns/{code}/returned', [AdminController::class, 'markReturned'])->name('admin.products.mark_returned')
            ->where('code', 'RNT[A-Z0-9]+');
    });
});
