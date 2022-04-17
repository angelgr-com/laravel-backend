<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Laravel Passport Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', [AuthController::class, 'userInfo'])->middleware('auth:api');
Route::post('/forget', [AuthController::class, 'forget']);
Route::post('/reset', [AuthController::class, 'reset']);
Route::post('/reset/{pincode}', [AuthController::class, 'reset']);