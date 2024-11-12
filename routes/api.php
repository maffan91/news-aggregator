<?php

use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\UserPreferenceController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route middleware
Route::middleware('auth:sanctum')->group(function () {

    //Article routes
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);

    //User routes
    Route::post('/logout', [AuthController::class, 'logout']);

    // User Preferences routes
    Route::get('/user/preferences', [UserPreferenceController::class, 'index']);
    Route::post('/user/preferences', [UserPreferenceController::class, 'store']);
});


//Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');


