<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PassportAuthController;
use App\Http\Controllers\API\ForgetController;

Route::get('/hi', function () {
    return 'hi!';
});

// Laravel Passport Routes
Route::post('/register', [PassportAuthController::class, 'register']);
Route::post('/login', [PassportAuthController::class, 'login']);
Route::post('/forget', [ForgetController::class, 'forget']);
Route::post('/reset', [ResetController::class, 'reset']);

