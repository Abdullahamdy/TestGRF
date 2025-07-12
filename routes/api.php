<?php

use App\Http\Controllers\Site\ContactUsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('create-contact-us', [ContactUsController::class, 'createTicket']);
