<?php

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\FormController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('api')->group(function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('forget_password', [AuthController::class, 'forgot_password']);
});

Route::middleware(['api', 'auth:api'])->group(function () {
    //auth related routes
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('refresh', [AuthController::class, 'refresh']);
    Route::post('update_profile', [AuthController::class, 'update_profile']);
    Route::post('change_password', [AuthController::class, 'change_password']);

    //Routes
    Route::get('home_data', [ApiController::class, 'home_data']);
    Route::post('single_sync', [FormController::class, 'single_sync']);
    Route::post('sync_data', [FormController::class, 'sync_data']);
    Route::get('completed_checklists', [FormController::class, 'get_completed_checklists']);
});