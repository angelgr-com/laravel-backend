<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\MessageController;

// User routes (players / Laravel Passport)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::group(
    [
        'middleware' => 'auth:api'
    ],
    function() {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::put('/profile/edit', [AuthController::class, 'editProfile']);
        Route::post('/forget', [AuthController::class, 'forget']);
        Route::post('/reset', [AuthController::class, 'reset']);
        Route::get('/reset/{pincode}', [AuthController::class, 'reset']);
    }
);

// Games routes
Route::group(
    [
        'middleware' => 'auth:api'
    ],
    function() {
        Route::get('/games', [GameController::class, 'index']);
        Route::post('/games/new', [GameController::class, 'store']);
        Route::get('/games/{game_title}', [GameController::class, 'show']);
        Route::put('/games/{game_title}', [GameController::class, 'update']);
        Route::delete('/games/{game_title}', [GameController::class, 'destroy']);
    }
);

// Parties routes
Route::group(
    [
        'middleware' => 'auth:api'
    ],
    function() {
        Route::get('/parties', [PartyController::class, 'index']);
        Route::post('/parties/new', [PartyController::class, 'store']);
        Route::get('/parties/{party_name}', [PartyController::class, 'show']);
        Route::get('/parties/game/{game_title}', [PartyController::class, 'findByGame']);
        Route::post('/parties/join/{party_name}/', [PartyController::class, 'joinParty']);
        Route::post('/parties/leave/{party_name}/', [PartyController::class, 'leaveParty']);
        Route::put('/parties/update/{party_name}', [PartyController::class, 'update']);
        Route::delete('/parties/{party_name}', [PartyController::class, 'destroy']);
    }
);

// Messages Routes
Route::group(
    [
        'middleware' => 'auth:api'
    ],
    function() {
        Route::get('/messages', [MessageController::class, 'index']);
        Route::post('/messages/new', [MessageController::class, 'store']);
        Route::get('/messages/{uuid}', [MessageController::class, 'show']);
        Route::get('/messages/party/{party_name}', [MessageController::class, 'showPartyMessages']);
        Route::put('/messages/update', [MessageController::class, 'update']);
        Route::delete('/messages/delete/{uuid}', [MessageController::class, 'destroy']);
    }
);