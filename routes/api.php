<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GithubController;
use App\Http\Controllers\RepositoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::apiResource('repositories', RepositoryController::class)
    ->except(['destroy', 'store'])
    ->middleware('auth:api');

Route::post('sync', [GithubController::class, 'sync'])
    ->middleware(['auth:api', 'throttle:1,1']);
