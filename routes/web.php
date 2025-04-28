<?php

use Illuminate\Support\Facades\Route;

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







    /* Route::post('/add-product', [CartController::class, 'addProductToCart'])->name('addProduct');
    Route::post('/decrease-product', [CartController::class, 'decreaseProductFromCart'])->name('decreaseProduct');
    Route::get('/profile', [UserController::class, 'getProfile'])->name('profile');
    Route::get('/edit-profile', [UserController::class, 'getEditProfile'])->name('editProfile.form');
    Route::post('/edit-profile', [UserController::class, 'editProfile'])->name('editProfile.submit');

    Route::get('/product/{id}', [ProductController::class, 'getProduct'])->name('product');
    Route::post('/add-review', [ProductController::class, 'addReview'])->name('addReview');*/
});
