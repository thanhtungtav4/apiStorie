<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\storiesController;
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
        Route::get('/lists', 'storiesController@lists');
        Route::get('/detail', 'storiesController@detail');
        Route::post('/create', 'storiesController@create');
    });

    Route::prefix('chapters')->group(function () {
        Route::get('/detail', 'chapterController@detail');
        Route::post('/create', 'chapterController@create');
    });

    Route::prefix('genres')->group(function () {
        Route::post('/create', 'genresController@create');
    });
    Route::prefix('auth')->group(function () {
        Route::post('/login', 'authenticatedController@login');
        Route::post('/register', 'authenticatedController@register');
        Route::middleware('auth:sanctum')->group( function () {
            Route::post('/logout', 'authenticatedController@logout');
        });
    });
});