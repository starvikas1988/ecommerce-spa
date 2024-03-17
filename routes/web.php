<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Display products
Route::get('/', 'App\Http\Controllers\ProductController@index')->name('products.index');

// Add product to cart
Route::post('/cart/add', 'App\Http\Controllers\CartController@addToCart')->name('cart.add');

// View cart
Route::get('/cart', 'App\Http\Controllers\CartController@startpoint')->name('products.cart');

// Remove product from cart
//Route::delete('/cart/remove/{id}', 'App\Http\Controllers\CartController@remove')->name('cart.remove');
Route::post('/cart/remove', 'App\Http\Controllers\CartController@remove')->name('cart.remove');

// Submit order
Route::get('/checkout', 'App\Http\Controllers\OrderController@checkout')->name('checkout');

Route::post('/place-order', 'App\Http\Controllers\OrderController@placeOrder')->name('place.order');


Route::get('/payment/success', [OrderController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/failed', [OrderController::class, 'paymentFailed'])->name('payment.cancel');

