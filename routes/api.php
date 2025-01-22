<?php

use App\Http\Controllers\Admin\Categories\CategoryController;
use App\Http\Controllers\Admin\Categories\SubCategoryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('products', [ProductController::class ,'index']);
Route::get('categories', [CategoryController::class ,'index']);
Route::get('sub-categories', [SubCategoryController::class ,'index']);