<?php

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TimesheetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Protected routes using Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // User API routes
    Route::apiResource('users', UserController::class);

    // Project API routes
    Route::apiResource('projects', ProjectController::class);

    // Timesheet API routes
    Route::apiResource('timesheets', TimesheetController::class);

    // Logout route
    Route::post('/logout', [UserController::class, 'logout']);
});