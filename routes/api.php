<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\UserController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/register', [UserController::class, 'register']); 
Route::post('/login', [UserController::class, 'login']); //login
Route::get('/musics', [MusicController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/likes', [LikeController::class, 'index']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [UserController::class, 'logout']);

    Route::post('/musics', [MusicController::class, 'store']);
    Route::put('/musics/{music}', [MusicController::class, 'update']);
    Route::delete('/musics/{music}', [MusicController::class, 'destroy']);

    Route::post('/likes', [LikeController::class, 'store']);
    Route::delete('/likes/{like}', [LikeController::class, 'destroy']);
});
