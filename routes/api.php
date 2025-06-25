<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChallengeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function () {
        return response()->json(Auth::user());
    });

    Route::prefix('challenges')->group(function () {
        Route::get('/', [ChallengeController::class, 'index']);
        Route::post('/', [ChallengeController::class, 'store']);
        Route::get('/{id}', [ChallengeController::class, 'show']);

        Route::post('/{challenge}/tasks/{task}/complete', [ChallengeController::class, 'completeTask']);
    });
});
