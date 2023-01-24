<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Masters\DistrictController;
use App\Http\Controllers\Masters\GramPanchayatController;
use App\Http\Controllers\Masters\QuestionController;
use App\Http\Controllers\Masters\TalukaController;
use App\Http\Controllers\Masters\VillageController;
use App\Http\Controllers\Survey\SurveyController;
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
        Route::middleware('can:isAdmin')->group(function () {
            Route::post('auth/register', 'register')->middleware('can:isAdmin');    // Employee Registrations                        // Login User
            Route::post('auth/v1/get-all-employees', 'getAllEmployees');                 // Employee Lists
            Route::post('auth/v1/employee-dtls', 'employeeDtls');                   // Employee Details
            Route::post('auth/v1/edit', 'edit');                            // Edit Employee Details
        });
        Route::post('auth/logout', 'logout');
        Route::post('auth/v1/change-password', 'changePassword');               // Change Password
    });

    // District
    Route::controller(DistrictController::class)->group(function () {
        Route::post('masters/states/v1/get-all', 'retriveStates');   // Get All States
        Route::post('masters/district/v1/get-all', 'retriveAll'); // Retrieve All
        Route::post('masters/district/v1/get-district-by-state', 'getDistrictByState'); // Get District By State
    });

    // Taluka
    Route::controller(TalukaController::class)->group(function () {
        Route::post('masters/taluka/v1/get-by-id', 'show');     // Retrieve Taluka by id
        Route::post('masters/taluka/v1/get-all', 'retrieveAll'); // Get All Taluka List
        Route::post('masters/taluka/v1/get-taluka-by-district', 'getTalukaByDistrict'); // Get Taluka by District
    });

    // Gram Panchayat
    Route::controller(GramPanchayatController::class)->group(function () {
        Route::post('masters/panchayat/v1/show', 'show');  // Add new Panchayat
        Route::post('masters/panchayat/v1/retrieve', 'retrieve');  // Add new Panchayat
        Route::post('masters/panchayat/v1/get-by-taluka', 'getByTaluka');    // Get Panchayat by Taluka
    });

    // Village Masters
    Route::controller(VillageController::class)->group(function () {
        Route::post('masters/village/v1/show', 'show');             // show Village
        Route::post('masters/village/v1/retrieve', 'retrieve');     // Retrieve Village
        Route::post('masters/village/v1/get-by-panchayat', 'getByPanchayat');    // Get Village by Panchayat id
    });

    // Admin Authorized Routes here 
    Route::middleware('can:isAdmin')->group(function () {
        // District
        Route::controller(DistrictController::class)->group(function () {
            Route::post('masters/district/v1/store', 'store');           // Add New District
            Route::post('masters/district/edit', 'edit');                // Edit District
        });

        // Taluka
        Route::controller(TalukaController::class)->group(function () {
            Route::post('masters/taluka/v1/store', 'store');
            Route::post('masters/taluka/v1/edit', 'edit');          // Edit Taluka
        });

        // Gram Panchayat
        Route::controller(GramPanchayatController::class)->group(function () {
            Route::post('masters/panchayat/v1/store', 'store');  // Add new Panchayat
            Route::post('masters/panchayat/v1/edit', 'edit');  // Add new Panchayat
        });

        // Village Masters
        Route::controller(VillageController::class)->group(function () {
            Route::post('masters/village/v1/store', 'store');  // Add New Village
            Route::post('masters/village/v1/edit', 'edit');      // Edit Village
        });
    });

    // Questions
    Route::controller(QuestionController::class)->group(function () {
        Route::post('questions/v1/get-all-questions', 'getAllQuestions');
    });

    // Start Survey 
    Route::controller(SurveyController::class)->group(function () {
        Route::post('survey/v1/store', 'store');    // Add Survey Record
        Route::post('survey/v1/list-survey', 'listSurvey');   // List of Surveys
        Route::post('survey/v1/get-survey-by-employee', 'getSurveyByEmployee');  // Get Survey By Employee ID
        Route::post('survey/v1/get-survey-by-village', 'getSurveyByVillage');   // Get all the surveys by village
    });
});
