<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CouponController;

Route::redirect('/dashboard', '/')->name('dashboard');

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('category/{id}', [CategoryController::class, 'show'])->name('category.show');

// Search Route
Route::get('/search', [ProductController::class, 'search'])->name('search.index');



// Cart Routes
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::get('/cart/json', function () {
    return response()->json([
        'cart_items' => session('cart', []),
    ]);
});

// Product Filter
Route::get('/products', [ProductController::class, 'filter'])->name('filter.index');

// Coupon Routes
Route::post('/coupon', [CouponController::class, 'store'])->name('coupon.store');
Route::delete('/coupon', [CouponController::class, 'destroy'])->name('coupon.destroy');



// Protected Routes (Login required)
Route::middleware(['auth'])->group(function () {
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

    Route::get('/my-orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{order}/invoice', [CustomerOrderController::class, 'downloadInvoice'])->name('orders.invoice');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Product Review Route
    Route::post('/product/{product}/review', [ReviewController::class, 'store'])->name('review.store');

    // Wishlist Routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});
// for invoice
Route::get('/my-orders/{order}/invoice', [CustomerOrderController::class, 'downloadInvoice'])
    ->middleware(['auth'])
    ->name('orders.invoice');

// for chart js
// Route::get('/admin/chart', [DashboardController::class, 'chart'])->name('admin.chart');
// Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');



require __DIR__ . '/auth.php';


// Route::get('/', function () {
//     return view('welcome');
// });


// Route::get('/', function () {
//     return view('welcome');
// });



// Route::post('/checkout', [CartController::class, 'checkout'])->middleware('auth');