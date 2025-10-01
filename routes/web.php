<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailTestController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/signUp', [\App\Http\Controllers\UserController::class, 'getSignUpForm']);
Route::post('/signUp', [\App\Http\Controllers\UserController::class, 'signUp']);

Route::get('/login', [\App\Http\Controllers\UserController::class, 'getLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\UserController::class, 'login']);

Route::middleware('auth')->group(function () {

    Route::get('/catalog', [\App\Http\Controllers\ProductController::class, 'getCatalog']);
    Route::post('/catalog', [\App\Http\Controllers\ProductController::class, 'catalog']);

    Route::get('/logout', [\App\Http\Controllers\UserController::class, 'logout']);

    Route::get('/profile', [\App\Http\Controllers\UserController::class, 'getProfile'])->name('profile');;
    Route::get('/editProfile', [\App\Http\Controllers\UserController::class, 'getEditProfile']);
    Route::post('/editProfile', [\App\Http\Controllers\UserController::class, 'editProfile']);

    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'cart'])->name('cart');

    Route::post('/add-product', [\App\Http\Controllers\CartController::class, 'addProductToCart']);
    Route::post('/decrease-product', [\App\Http\Controllers\CartController::class, 'decreaseProductFromCart']);

    Route::get('/product/{product}/reviews', [App\Http\Controllers\ProductController::class, 'getProductReviews'])->name('product.show');
    Route::post('/reviews', [App\Http\Controllers\ProductController::class, 'addReviews'])->name('review.store');

    Route::get('/order', [\App\Http\Controllers\OrderController::class, 'getOrder'])->name('order');
    Route::post('/order', [\App\Http\Controllers\OrderController::class, 'order']);

    Route::get('/orderProducts', [\App\Http\Controllers\OrderController::class, 'getAllOrders'])->name('orderProducts');
    Route::post('/orderProducts', [\App\Http\Controllers\OrderController::class, 'orderProducts']);

    Route::get('/mail/test', [\App\Http\Controllers\TestMailController::class, 'send']);
    Route::get('/mail/receive', [\App\Http\Controllers\TestMailController::class, 'receive']);
    Route::get('/send-test-email', [MailTestController::class, 'send']);

});
