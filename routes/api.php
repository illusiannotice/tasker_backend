<?php


use  Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TaskController;

Route::post('/login', [UsersController::class, 'login']);
Route::post('/register', [UsersController::class, 'register']);


Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout', [UsersController::class, 'logout']);
    Route::get('/user', [UsersController::class, 'getUser']);
    Route::apiResource('tasks', TaskController::class);
});