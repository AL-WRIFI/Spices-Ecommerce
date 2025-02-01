<?php

use App\Http\Controllers\Admin\Categories\CategoryController;
use App\Http\Controllers\Admin\Categories\SubCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\FavoriteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('products', [ProductController::class ,'index']);
Route::get('categories', [CategoryController::class ,'index']);
Route::get('sub-categories', [SubCategoryController::class ,'index']);


Route::prefix('/user')->group(function () {
    
    Route::controller(AuthController::class)->prefix('auth')->group(function(){
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::post('/verifyOtp', 'verifyOtp');
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

    Route::controller(OrderController::class)->prefix('order')->middleware('auth:sanctum')->group(function(){
        Route::post('/create','store');
    });


});
