<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\MessageController;

// Laravel Passport Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', [AuthController::class, 'userInfo'])->middleware('auth:api');
Route::post('/forget', [AuthController::class, 'forget']);
Route::post('/reset', [AuthController::class, 'reset']);
Route::post('/reset/{pincode}', [AuthController::class, 'reset']);

// Games Routes
Route::get('/games', [GameController::class, 'index']);
Route::post('/games/new', [GameController::class, 'store']);
Route::get('/games/{game_title}', [GameController::class, 'show']);
Route::put('/games/{game_title}', [GameController::class, 'update']);
Route::delete('/games/{game_title}', [GameController::class, 'destroy']);

// Parties Routes
Route::get('/parties', [PartyController::class, 'index']);
Route::post('/parties/new', [PartyController::class, 'store']);
Route::get('/parties/{party_name}', [PartyController::class, 'show']);
Route::get('/parties/game/{game_title}', [PartyController::class, 'showByGame']);
Route::put('/parties/update/{party_name}', [PartyController::class, 'update']);
Route::delete('/parties/{party_name}', [PartyController::class, 'destroy']);

// Messages Routes
Route::get('/messages', [MessageController::class, 'index']);
Route::post('/messages/new', [MessageController::class, 'store']);
Route::get('/messages/{uuid}', [MessageController::class, 'show']);
Route::put('/messages/update', [MessageController::class, 'update']);
Route::delete('/messages/delete/{uuid}', [MessageController::class, 'destroy']);