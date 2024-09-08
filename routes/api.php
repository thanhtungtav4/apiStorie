<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoriesController;
use App\Http\Controllers\Api\ChapterController;
use App\Http\Controllers\Api\AuthenticatedController;
use App\Http\Controllers\Api\TagsController;
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
        Route::get('/lists', 'GenresController@lists');
        Route::middleware('auth:sanctum')->group( function () {
            Route::post('/create', 'GenresController@create');
        });
    });

    Route::prefix('tags')->group(function () {
        Route::get('/lists', 'TagsController@lists');
        Route::middleware('auth:sanctum')->group( function () {
            Route::post('/create', 'TagsController@create');
        });
    });


    Route::prefix('authors')->group(function () {
        Route::get('/lists', 'AuthorsController@lists');
        //Route to get author details by ID or slug
        Route::get('/detail', 'AuthorsController@detail');
        Route::middleware('auth:sanctum')->group( function () {
            Route::post('/create', 'AuthorsController@create');
        });
    });


    Route::prefix('auth')->group(function () {
        Route::post('/login', 'AuthenticatedController@login');
        Route::post('/register', 'AuthenticatedController@register');
        Route::middleware('auth:sanctum')->group( function () {
            Route::get('/user', 'AuthenticatedController@user');
            Route::post('/logout', 'AuthenticatedController@logout');
            Route::post('/change-password', 'AuthenticatedController@changePassword');
            Route::post('/refresh', 'AuthenticatedController@refreshToken');
        });
    });

    Route::prefix('crawl')->group(function () {
        Route::middleware('auth:sanctum')->group( function () {
            Route::get('/getstories', 'CrawlController@getList');
            Route::get('/test', 'CrawlController@saveCrawlChaperJob');
        });
    });
});