<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\DashboardController;

Route::redirect('/dashboard', '/')->name('dashboard');

Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('category/{id}', [CategoryController::class, 'show'])->name('category.show');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

Route::middleware(['auth'])->group(function () {
    Route::get('/my-orders', [CustomerOrderController::class, 'index'])->name('orders.index');
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');
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