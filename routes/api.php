<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Masters\TalukaController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('auth/login', 'login');
    Route::post('auth/register', 'register');                        // User Registrations                        // Login User
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('auth/logout', 'logout');
    });

    // Taluka
    Route::controller(TalukaController::class)->group(function () {
        Route::post('masters/taluka/v1/store', 'store');    // Add New Taluka
        Route::post('masters/taluka/v1/edit', 'edit');  // Edit Taluka
        Route::post('masters/taluka/v1/get-by-id', 'show');  // Retrieve Taluka by id
        Route::post('masters/taluka/v1/get-all', 'retrieveAll'); // Get All Taluka List
    });
});
