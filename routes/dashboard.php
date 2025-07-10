<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    //
    Route::post('login', [App\Http\Controllers\Dashboard\Auth\AuthenticationController::class, 'login']);
    Route::post('forgot', [App\Http\Controllers\Dashboard\Auth\AuthenticationController::class, 'forgot']);
    Route::post('reset', [App\Http\Controllers\Dashboard\Auth\AuthenticationController::class, 'reset']);
    Route::post('reset-password', [App\Http\Controllers\Dashboard\Auth\AuthenticationController::class, 'resetPassword']);
});

Route::namespace('App\Http\Controllers\Dashboard')->middleware(['auth:sanctum'])->group(function () {

    Route::resource('category', 'CategoryController')->except(['edit', 'create']);
    Route::resource('lfii', 'LIFIController')->except(['edit', 'create']);
    Route::resource('tag', 'TagController')->except(['edit', 'create']);
    Route::resource('user', 'UserController')->except(['edit', 'create']);
    Route::resource('news', 'NewsController')->except(['edit', 'create']);
     Route::get('featured-news', 'NewsController@getFeatured');

});
