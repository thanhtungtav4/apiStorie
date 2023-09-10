<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoriesController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\AuthenticatedController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->namespace('App\Http\Controllers\Api')->group(function () {
    Route::prefix('stories')->group(function () {
        Route::get('/lists', 'StoriesController@lists');
        Route::get('/detail', 'StoriesController@detail');
        Route::middleware('auth:sanctum')->group( function () {
            Route::post('/create', 'StoriesController@create');
        });
    });

    Route::prefix('chapters')->group(function () {
        Route::get('/detail', 'ChapterController@detail');
        Route::middleware('auth:sanctum')->group( function () {
            Route::post('/create', 'ChapterController@create');
        });
    });

    Route::prefix('genres')->group(function () {
        Route::middleware('auth:sanctum')->group( function () {
            Route::post('/create', 'GenresController@create');
        });
    });
    Route::prefix('auth')->group(function () {
        Route::post('/login', 'AuthenticatedController@login');
        Route::post('/register', 'AuthenticatedController@register');
        Route::middleware('auth:sanctum')->group( function () {
            Route::post('/logout', 'AuthenticatedController@logout');
            Route::post('/change-password', 'AuthenticatedController@change_password');
        });
    });
});