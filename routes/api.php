<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Masters\GramPanchayatController;
use App\Http\Controllers\Masters\TalukaController;
use GuzzleHttp\Middleware;
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
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('auth/register', 'register')->middleware('can:isAdmin');               // User Registrations                        // Login User
        Route::post('auth/logout', 'logout');
    });

    // Admin Authorized Routes here
    Route::middleware('can:isAdmin')->group(function () {
        // Taluka
        Route::controller(TalukaController::class)->group(function () {
            Route::post('masters/taluka/v1/store', 'store');
            Route::post('masters/taluka/v1/edit', 'edit');  // Edit Taluka
            Route::post('masters/taluka/v1/get-by-id', 'show');  // Retrieve Taluka by id
            Route::post('masters/taluka/v1/get-all', 'retrieveAll'); // Get All Taluka List
        });

        // Gram Panchayat
        Route::controller(GramPanchayatController::class)->group(function () {
            Route::post('masters/panchayat/v1/store', 'store');  // Add new Panchayat
            Route::post('masters/panchayat/v1/edit', 'edit');  // Add new Panchayat
            Route::post('masters/panchayat/v1/show', 'show');  // Add new Panchayat
            Route::post('masters/panchayat/v1/retrieve', 'retrieve');  // Add new Panchayat
        });
    });
});
