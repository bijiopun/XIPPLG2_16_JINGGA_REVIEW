<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoanController;

Route::apiResource('categories', CategoryController::class);
Route::apiResource('reviews', ReviewController::class);
Route::apiResource('books', BookController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('loans', LoanController::class);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
