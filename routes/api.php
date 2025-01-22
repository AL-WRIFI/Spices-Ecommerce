<?php

use App\Http\Controllers\Admin\Categories\CategoryController;
use App\Http\Controllers\Admin\Categories\SubCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\User\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('products', [ProductController::class ,'index']);
Route::get('categories', [CategoryController::class ,'index']);
Route::get('sub-categories', [SubCategoryController::class ,'index']);


Route::prefix('/user')->group(function () {
    
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verifyOtp', [AuthController::class, 'verifyOtp']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart/add', [CartController::class, 'addItem']);
        Route::put('/cart/update/{itemId}', [CartController::class, 'updateItem']);
        Route::delete('/cart/remove/{itemId}', [CartController::class, 'removeItem']);
        Route::delete('/cart/clear', [CartController::class, 'clearCart']);
    });
});

