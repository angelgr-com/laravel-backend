<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ForgetController;
use App\Http\Controllers\API\ResetController;
use App\Http\Controllers\API\UserController;

Route::get('/hi', function () {
    return 'hi!';
});

// Laravel Passport Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', [AuthController::class, 'userInfo']);
Route::post('/forget', [ForgetController::class, 'forget']);
Route::post('/reset', [ResetController::class, 'reset']);

