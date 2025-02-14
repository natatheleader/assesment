<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TimesheetController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:api')->group(function () {
    // Auth
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    // Users
    Route::apiResource('users', UserController::class);
    
    // Projects
    Route::apiResource('projects', ProjectController::class);
    Route::get('projects/{project}/users', [ProjectController::class, 'users']);
    Route::post('projects/{project}/users', [ProjectController::class, 'attachUsers']);
    Route::delete('projects/{project}/users/{user}', [ProjectController::class, 'detachUser']);

    // Timesheets
    Route::apiResource('timesheets', TimesheetController::class);
    Route::get('projects/{project}/timesheets', [TimesheetController::class, 'projectTimesheets']);
    Route::get('users/{user}/timesheets', [TimesheetController::class, 'userTimesheets']);

    // Attributes (EAV)
    Route::apiResource('attributes', AttributeController::class);
    
    // Additional EAV routes
    Route::get('projects/{project}/attributes', [ProjectController::class, 'attributes']);
    Route::post('projects/{project}/attributes', [ProjectController::class, 'updateAttributes']);
});