<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResultsController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/results', [ResultsController::class, 'index']);
});