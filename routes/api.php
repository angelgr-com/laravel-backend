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

// Games Routes
Route::get('/games', [GameController::class, 'getGames']);
Route::get('/games/{title}', [GameController::class, 'getGame']);
Route::post('/games', [GameController::class, 'newGame']);
Route::put('/games/{title}', [GameController::class, 'editGame']);
Route::delete('/games/{title}', [GameController::class, 'deleteGame']);

// Parties Routes
Route::get('/parties', [PartyController::class, 'getParties']);
Route::get('/parties/{title}', [PartyController::class, 'getParty']);
Route::post('/parties', [PartyController::class, 'newParty']);
Route::put('/parties/{title}', [PartyController::class, 'editParty']);
Route::delete('/parties/{title}', [PartyController::class, 'deleteParty']);

// Messages Routes
Route::get('/messages', [MessageController::class, 'getMessages']);
Route::get('/messages/{title}', [MessageController::class, 'getMessage']);
Route::post('/messages', [MessageController::class, 'newMessage']);
Route::put('/messages/{title}', [MessageController::class, 'editMessage']);
Route::delete('/messages/{title}', [MessageController::class, 'deleteMessage']);