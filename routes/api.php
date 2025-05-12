<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Comment\CommentController;
use App\Http\Controllers\Api\v1\Post\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::group(['prefix' => 'posts'], function () {
    Route::get('/', [PostController::class, 'index']);
    Route::get('/search', [PostController::class, 'search']);
    Route::get('/{id}', [PostController::class, 'show']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/', [PostController::class, 'store']);
        Route::put('/{id}', [PostController::class, 'update']);
        Route::delete('/{id}', [PostController::class, 'destroy']);
    });

    Route::group(['prefix' => '{postId}/comments','middleware' => 'auth:api'], function () {
            Route::post('/', [CommentController::class, 'store']);
            Route::put('/{id}', [CommentController::class, 'update']);
            Route::delete('/{id}', [CommentController::class, 'destroy']);
    });
});

