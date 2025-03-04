<?php

use App\Http\Controllers\Admin\Categories\CategoryController;
use App\Http\Controllers\Admin\Categories\SubCategoryController;
use App\Http\Controllers\Api\Coupon\CouponController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\User\CartController;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\Driver\AuthController as DriverAuthController;
use App\Http\Controllers\Api\User\FavoriteController;
use App\Http\Controllers\Api\User\OrderController;
use App\Http\Controllers\Api\User\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('/attribute')->group(function () {
    Route::get('categories', [CategoryController::class ,'index']);
    Route::get('sub-categories', [SubCategoryController::class ,'index']);
});


Route::post('products', [ProductController::class ,'fetch']);



Route::prefix('/user')->group(function () {
    
    Route::controller(AuthController::class)->prefix('auth')->group(function(){
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::post('/verifyOtp', 'verifyOtp');
    });

    
    Route::controller(ProfileController::class)->prefix('profile')->middleware('auth:sanctum')->group(function(){
        Route::get('/','show')->name('profile.show');
        Route::put('/','update')->name('profile.update');
        Route::post('/change-password','changePassword');
    });

    Route::controller(CartController::class)->prefix('cart')->middleware('auth:sanctum')->group(function(){
        Route::get('/','index');
        Route::post('/','addItem');
        Route::put('/{itemId}','updateItem');
        Route::delete('/{itemId}','removeItem');
        Route::post('/clear', 'clearCart');
    });

    Route::controller(FavoriteController::class)->prefix('favorites')->middleware('auth:sanctum')->group(function(){
        Route::get('/','index');
        Route::post('/','addFavorite');
        Route::delete('/{favoriteId}','removeFavorite');
    });

    Route::controller(OrderController::class)->prefix('orders')->middleware('auth:sanctum')->group(function(){
        Route::get('/ongoing-order','ongoing');
        Route::get('/','fetch');
        Route::post('/create','store');
        Route::get('/{order_id}', 'show');
        Route::put('/cancel/{order_id}', 'cancelOrder');
    });


    Route::controller(CouponController::class)->prefix('coupon')->middleware('auth:sanctum')->group(function(){
        Route::post('/apply','apply');
        Route::post('/remove','remove');

        Route::post('create','store');
        Route::put('update/{id}','update');
    });

});



Route::prefix('/driver')->group(function () {
    
    Route::controller(DriverAuthController::class)->prefix('auth')->group(function(){
        Route::post('/login', 'login');
    });
    
    Route::controller(ProfileController::class)->prefix('profile')->middleware('auth:sanctum')->group(function(){
        Route::get('/','show')->name('profile.show');
        Route::put('/','update')->name('profile.update');
        Route::post('/change-password','changePassword');
    });

    Route::controller(OrderController::class)->prefix('orders')->middleware('auth:sanctum')->group(function(){
        Route::get('/ongoing-order','ongoing');
        Route::get('/','fetch');
        Route::post('/create','store');
        Route::get('/{order_id}', 'show');
        Route::put('/cancel/{order_id}', 'cancelOrder');
    });
});